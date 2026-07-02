<?php

namespace App\Services;

use App\Contracts\DocumentSigningService as DocumentSigningServiceContract;
use App\Models\ClientDocument;
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
                        $this->signatureFieldFor($document->document_type),
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

        $expected = hash_hmac('md5', $payload, $secret);

        return hash_equals($expected, $signature);
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
    public function signatureFieldFor(string $documentType): array
    {
        $placement = config("annature.document_type_placement.{$documentType}")
            ?? config('annature.signature_placement', 'coordinates');

        $field = ['type' => 'signature'];

        if ($placement === 'anchor') {
            return array_merge($field, [
                'anchor' => config('annature.anchor', '{{signature}}'),
            ]);
        }

        return array_merge($field, config('annature.signature_field', []));
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
