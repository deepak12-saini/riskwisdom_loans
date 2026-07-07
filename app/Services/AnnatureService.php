<?php

namespace App\Services;

use App\Contracts\DocumentSigningService as DocumentSigningServiceContract;
use App\Models\ClientDocument;
use App\Support\PdfSignaturePlacement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AnnatureService implements DocumentSigningServiceContract
{
    public function provider(): string
    {
        return 'annature';
    }

    public function providerLabel(): string
    {
        return 'Annature';
    }

    public function isConfigured(): bool
    {
        if (! config('annature.enabled')) {
            return false;
        }

        return filled(config('annature.public_key'))
            && filled(config('annature.private_key'))
            && filled(config('annature.account_id'));
    }

    /**
     * @return array{envelope_id: string, status: string}
     */
    public function sendDocument(ClientDocument $document): array
    {
        $this->ensureConfigured();

        $pdfContents = Storage::disk($document->original_disk)->get($document->original_path);
        $base64 = base64_encode($pdfContents);
        $signatureField = $this->signatureFieldFor($document->document_type, $pdfContents);

        $payload = [
            'name' => $document->title,
            'message' => 'Please review and sign: '.$document->title,
            'draft' => false,
            'account_id' => config('annature.account_id'),
            'documents' => [
                [
                    'name' => $document->title.'.pdf',
                    'base' => $base64,
                ],
            ],
            'recipients' => [
                [
                    'name' => $document->signer_name,
                    'email' => $document->signer_email,
                    'type' => 'signer',
                    'order' => 1,
                    'fields' => [
                        $signatureField,
                    ],
                ],
            ],
            'metadata' => [
                'client_document_id' => (string) $document->id,
                'client_id' => (string) $document->client_id,
            ],
        ];

        $response = $this->api()->post('/envelopes', $payload)->throw();

        return [
            'envelope_id' => (string) $response->json('id'),
            'status' => strtolower((string) $response->json('status', 'sent')),
        ];
    }

    public function syncEnvelopeStatus(ClientDocument $document): ClientDocument
    {
        $this->ensureConfigured();

        if (! $document->envelope_id) {
            throw new RuntimeException('Document has no Annature envelope ID.');
        }

        $response = $this->api()->get('/envelopes/'.$document->envelope_id)->throw();
        $status = strtolower((string) $response->json('status', $document->status));

        $document->status = $this->mapEnvelopeStatus($status);
        $document->metadata = array_merge($document->metadata ?? [], [
            'annature_status' => $status,
            'synced_at' => now()->toIso8601String(),
        ]);

        if ($document->status === 'signed') {
            $this->storeSignedPdf($document, $response->json());
        }

        $document->save();

        return $document->fresh();
    }

    public function handleWebhookPayload(array $payload): void
    {
        $envelopeId = data_get($payload, 'envelope_id');
        $event = strtolower((string) data_get($payload, 'event', ''));

        if (! $envelopeId) {
            return;
        }

        $document = ClientDocument::query()
            ->where('envelope_id', $envelopeId)
            ->first();

        if (! $document) {
            Log::info('Annature webhook: unknown envelope', ['envelope_id' => $envelopeId]);

            return;
        }

        if (in_array($event, ['recipient_completed', 'envelope_completed'], true)) {
            try {
                $this->syncEnvelopeStatus($document);
            } catch (\Throwable $exception) {
                Log::error('Annature webhook: sync failed', [
                    'document_id' => $document->id,
                    'message' => $exception->getMessage(),
                ]);
            }

            return;
        }

        if ($event === 'recipient_declined') {
            $document->status = 'declined';
            $document->metadata = array_merge($document->metadata ?? [], [
                'last_webhook_at' => now()->toIso8601String(),
                'annature_status' => 'declined',
                'declined_reason' => data_get($payload, 'declined_reason'),
            ]);
            $document->save();

            return;
        }

        $document->metadata = array_merge($document->metadata ?? [], [
            'last_webhook_at' => now()->toIso8601String(),
            'annature_event' => $event,
        ]);
        $document->save();
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        $secret = config('annature.webhook_secret');

        if (! filled($secret)) {
            return true;
        }

        if (! filled($signature)) {
            return false;
        }

        $signature = trim($signature);
        $candidates = [
            hash_hmac('sha256', $payload, $secret),
            'sha256='.hash_hmac('sha256', $payload, $secret),
            hash_hmac('md5', $payload, $secret),
            'md5='.hash_hmac('md5', $payload, $secret),
            base64_encode(hash_hmac('sha256', $payload, $secret, true)),
            'sha256='.base64_encode(hash_hmac('sha256', $payload, $secret, true)),
            base64_encode(hash_hmac('md5', $payload, $secret, true)),
            'md5='.base64_encode(hash_hmac('md5', $payload, $secret, true)),
        ];

        foreach ($candidates as $candidate) {
            if (hash_equals($candidate, $signature)) {
                return true;
            }
        }

        Log::warning('Annature webhook signature mismatch', [
            'received_signature' => $signature,
            'received_signature_length' => strlen($signature),
            'payload_length' => strlen($payload),
            'expected_sha256_hex' => $candidates[0],
            'expected_sha256_prefixed' => $candidates[1],
            'expected_md5_hex' => $candidates[2],
            'expected_md5_prefixed' => $candidates[3],
            'expected_sha256_base64' => $candidates[4],
            'expected_sha256_prefixed_base64' => $candidates[5],
            'expected_md5_base64' => $candidates[6],
            'expected_md5_prefixed_base64' => $candidates[7],
        ]);

        return false;
    }

    /**
     * @param  array<string, mixed>|null  $envelope
     */
    public function storeSignedPdf(ClientDocument $document, ?array $envelope = null): void
    {
        $this->ensureConfigured();

        if (! $document->envelope_id) {
            throw new RuntimeException('Document has no Annature envelope ID.');
        }

        if ($envelope === null) {
            $response = $this->api()->get('/envelopes/'.$document->envelope_id)->throw();
            $envelope = $response->json();
        }

        $downloadUrl = data_get($envelope, 'combined') ?? data_get($envelope, 'master');

        if (! filled($downloadUrl)) {
            throw new RuntimeException('Annature envelope has no downloadable PDF yet.');
        }

        $pdfResponse = Http::timeout(120)->get($downloadUrl);

        if ($pdfResponse->failed()) {
            throw new RuntimeException('Failed to download signed PDF from Annature.');
        }

        $disk = config('filesystems.default', 'local');
        $path = sprintf(
            'clients/%d/documents/%d-signed.pdf',
            $document->client_id,
            $document->id
        );

        Storage::disk($disk)->put($path, $pdfResponse->body());

        if ($document->signed_disk && $document->signed_path && $document->signed_path !== $path) {
            Storage::disk($document->signed_disk)->delete($document->signed_path);
        }

        $document->signed_disk = $disk;
        $document->signed_path = $path;
        $document->signed_at = now();
        $document->status = 'signed';
        $document->error_message = null;

        if ($document->task_id) {
            $document->task?->update([
                'status' => 'done',
                'closed_at' => now(),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function signatureFieldFor(string $documentType, ?string $pdfContents = null): array
    {
        $anchor = (string) config('annature.anchor', '{{signature}}');
        $placement = config("annature.document_type_placement.{$documentType}")
            ?? config('annature.signature_placement', 'coordinates');

        $useAnchor = $placement === 'anchor'
            || ($pdfContents !== null && PdfSignaturePlacement::containsAnchor($pdfContents, $anchor));

        if ($useAnchor) {
            return [
                'type' => 'signature',
                'anchor' => $anchor,
                'required' => true,
            ];
        }

        $dimensions = $pdfContents !== null
            ? PdfSignaturePlacement::dimensions($pdfContents)
            : ['width' => 595.0, 'height' => 842.0, 'pages' => 1];

        $defaults = array_merge(
            ['page' => $dimensions['pages']],
            config('annature.signature_field', [])
        );

        unset($defaults['x_coordinate'], $defaults['y_coordinate']);

        return PdfSignaturePlacement::coordinateField($dimensions, $defaults);
    }

    private function mapEnvelopeStatus(string $status): string
    {
        return match ($status) {
            'completed' => 'signed',
            'sent', 'created' => 'sent',
            'declined' => 'declined',
            'voided' => 'voided',
            default => $status !== '' ? $status : 'sent',
        };
    }

    private function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Annature is not configured. Add API keys to .env.');
        }
    }

    private function api()
    {
        return Http::baseUrl(config('annature.api_base_url'))
            ->withHeaders([
                'X-Annature-Id' => config('annature.public_key'),
                'X-Annature-Key' => config('annature.private_key'),
            ])
            ->acceptJson()
            ->asJson()
            ->timeout(60);
    }
}
