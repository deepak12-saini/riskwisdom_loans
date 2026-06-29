<?php

namespace App\Services;

use App\Models\ClientDocument;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DocuSignService
{
    public function isConfigured(): bool
    {
        if (! config('docusign.enabled')) {
            return false;
        }

        return filled(config('docusign.integration_key'))
            && filled(config('docusign.user_id'))
            && filled(config('docusign.account_id'))
            && $this->privateKey() !== null;
    }

    /**
     * @return array{envelope_id: string, status: string}
     */
    public function sendDocument(ClientDocument $document): array
    {
        $this->ensureConfigured();

        $pdfContents = Storage::disk($document->original_disk)->get($document->original_path);
        $base64 = base64_encode($pdfContents);

        $payload = [
            'emailSubject' => 'Please sign: '.$document->title,
            'documents' => [
                [
                    'documentBase64' => $base64,
                    'name' => $document->title,
                    'fileExtension' => 'pdf',
                    'documentId' => '1',
                ],
            ],
            'recipients' => [
                'signers' => [
                    [
                        'email' => $document->signer_email,
                        'name' => $document->signer_name,
                        'recipientId' => '1',
                        'routingOrder' => '1',
                        'tabs' => [
                            'signHereTabs' => [
                                [
                                    'documentId' => '1',
                                    'pageNumber' => '1',
                                    'xPosition' => '100',
                                    'yPosition' => '650',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'status' => 'sent',
        ];

        $response = $this->api()->post(
            '/v2.1/accounts/'.config('docusign.account_id').'/envelopes',
            $payload
        )->throw();

        return [
            'envelope_id' => (string) $response->json('envelopeId'),
            'status' => strtolower((string) $response->json('status', 'sent')),
        ];
    }

    public function syncEnvelopeStatus(ClientDocument $document): ClientDocument
    {
        $this->ensureConfigured();

        if (! $document->envelope_id) {
            throw new RuntimeException('Document has no DocuSign envelope ID.');
        }

        $response = $this->api()->get(
            '/v2.1/accounts/'.config('docusign.account_id').'/envelopes/'.$document->envelope_id
        )->throw();

        $status = strtolower((string) $response->json('status', $document->status));

        $document->status = $this->mapEnvelopeStatus($status);
        $document->metadata = array_merge($document->metadata ?? [], [
            'docusign_status' => $status,
            'synced_at' => now()->toIso8601String(),
        ]);

        if ($document->status === 'signed') {
            $this->storeSignedPdf($document);
        }

        $document->save();

        return $document->fresh();
    }

    public function handleWebhookPayload(array $payload): void
    {
        $envelopeId = data_get($payload, 'data.envelopeId')
            ?? data_get($payload, 'envelopeId');

        $status = strtolower((string) (
            data_get($payload, 'data.envelopeSummary.status')
            ?? data_get($payload, 'status')
            ?? ''
        ));

        if (! $envelopeId) {
            return;
        }

        $document = ClientDocument::query()
            ->where('envelope_id', $envelopeId)
            ->first();

        if (! $document) {
            Log::info('DocuSign webhook: unknown envelope', ['envelope_id' => $envelopeId]);

            return;
        }

        if ($status !== '') {
            $document->status = $this->mapEnvelopeStatus($status);
        }

        if ($document->status === 'signed') {
            try {
                $this->storeSignedPdf($document);
            } catch (\Throwable $exception) {
                Log::error('DocuSign webhook: failed to store signed PDF', [
                    'document_id' => $document->id,
                    'message' => $exception->getMessage(),
                ]);
                $document->error_message = $exception->getMessage();
            }
        }

        $document->metadata = array_merge($document->metadata ?? [], [
            'last_webhook_at' => now()->toIso8601String(),
            'docusign_status' => $status,
        ]);
        $document->save();

        if ($document->task_id && $document->status === 'signed') {
            $document->task?->update([
                'status' => 'done',
                'closed_at' => now(),
            ]);
        }
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        $secret = config('docusign.webhook_secret');

        if (! filled($secret)) {
            return true;
        }

        if (! filled($signature)) {
            return false;
        }

        $expected = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        return hash_equals($expected, $signature);
    }

    public function storeSignedPdf(ClientDocument $document): void
    {
        $this->ensureConfigured();

        if (! $document->envelope_id) {
            throw new RuntimeException('Document has no DocuSign envelope ID.');
        }

        $response = $this->api()->get(
            '/v2.1/accounts/'.config('docusign.account_id').'/envelopes/'.$document->envelope_id.'/documents/combined'
        )->throw();

        $disk = config('filesystems.default', 'local');
        $path = sprintf(
            'clients/%d/documents/%d-signed.pdf',
            $document->client_id,
            $document->id
        );

        Storage::disk($disk)->put($path, $response->body());

        if ($document->signed_disk && $document->signed_path && $document->signed_path !== $path) {
            Storage::disk($document->signed_disk)->delete($document->signed_path);
        }

        $document->signed_disk = $disk;
        $document->signed_path = $path;
        $document->signed_at = now();
        $document->status = 'signed';
        $document->error_message = null;
    }

    private function mapEnvelopeStatus(string $status): string
    {
        return match ($status) {
            'completed' => 'signed',
            'sent' => 'sent',
            'delivered' => 'delivered',
            'declined' => 'declined',
            'voided' => 'voided',
            default => $status !== '' ? $status : 'sent',
        };
    }

    private function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('DocuSign is not configured. Add API keys to .env.');
        }
    }

    private function api()
    {
        $token = $this->accessToken();

        return Http::baseUrl(config('docusign.api_base_url'))
            ->withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(60);
    }

    private function accessToken(): string
    {
        return Cache::remember('docusign.access_token', 3500, function () {
            $jwt = $this->buildJwt();
            $oauthBase = config('docusign.oauth_base_url');

            $response = Http::asForm()
                ->post($oauthBase.'/oauth/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if ($response->failed()) {
                throw new RuntimeException(
                    'DocuSign authentication failed: '.$response->body()
                );
            }

            return (string) $response->json('access_token');
        });
    }

    private function buildJwt(): string
    {
        $integrationKey = config('docusign.integration_key');
        $userId = config('docusign.user_id');
        $oauthHost = parse_url(config('docusign.oauth_base_url'), PHP_URL_HOST);
        $now = time();

        $header = $this->base64UrlEncode(json_encode([
            'typ' => 'JWT',
            'alg' => 'RS256',
        ], JSON_THROW_ON_ERROR));

        $payload = $this->base64UrlEncode(json_encode([
            'iss' => $integrationKey,
            'sub' => $userId,
            'aud' => $oauthHost,
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'signature impersonation',
        ], JSON_THROW_ON_ERROR));

        $input = $header.'.'.$payload;
        $privateKey = openssl_pkey_get_private($this->privateKey());

        if ($privateKey === false) {
            throw new RuntimeException('Invalid DocuSign private key.');
        }

        $signature = '';
        openssl_sign($input, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return $input.'.'.$this->base64UrlEncode($signature);
    }

    private function privateKey(): ?string
    {
        $inline = config('docusign.private_key');

        if (filled($inline)) {
            return str_replace('\\n', PHP_EOL, $inline);
        }

        $path = config('docusign.private_key_path');

        if (filled($path) && is_readable($path)) {
            return file_get_contents($path) ?: null;
        }

        return null;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
