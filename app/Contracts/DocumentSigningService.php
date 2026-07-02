<?php

namespace App\Contracts;

use App\Models\ClientDocument;

interface DocumentSigningService
{
    public function provider(): string;

    public function providerLabel(): string;

    public function isConfigured(): bool;

    /**
     * @return array{envelope_id: string, status: string}
     */
    public function sendDocument(ClientDocument $document): array;

    public function syncEnvelopeStatus(ClientDocument $document): ClientDocument;

    public function handleWebhookPayload(array $payload): void;

    public function verifyWebhookSignature(string $payload, ?string $signature): bool;
}
