<?php

namespace Tests\Unit;

use App\Services\AnnatureService;
use Tests\TestCase;

class AnnatureSignatureFieldTest extends TestCase
{
    public function test_webhook_signature_accepts_sha256_hex(): void
    {
        config([
            'annature.webhook_secret' => 'test-secret',
        ]);

        $payload = '{"event":"envelope_completed"}';
        $signature = hash_hmac('sha256', $payload, 'test-secret');

        $this->assertTrue(
            app(AnnatureService::class)->verifyWebhookSignature($payload, $signature)
        );
    }

    public function test_webhook_signature_accepts_sha256_prefixed_value(): void
    {
        config([
            'annature.webhook_secret' => 'test-secret',
        ]);

        $payload = '{"event":"envelope_completed"}';
        $signature = 'sha256='.hash_hmac('sha256', $payload, 'test-secret');

        $this->assertTrue(
            app(AnnatureService::class)->verifyWebhookSignature($payload, $signature)
        );
    }

    public function test_signature_field_uses_anchor_by_default(): void
    {
        config([
            'annature.signature_placement' => 'anchor',
            'annature.anchor' => '{{signature}}',
        ]);

        $field = app(AnnatureService::class)->signatureFieldFor('privacy_consent');

        $this->assertSame('signature', $field['type']);
        $this->assertSame('{{signature}}', $field['anchor']);
        $this->assertArrayNotHasKey('page', $field);
    }

    public function test_signature_field_uses_anchor_when_configured(): void
    {
        config([
            'annature.signature_placement' => 'coordinates',
            'annature.anchor' => '{{signature}}',
            'annature.document_type_placement' => [
                'privacy_consent' => 'anchor',
            ],
        ]);

        $field = app(AnnatureService::class)->signatureFieldFor('privacy_consent');

        $this->assertSame('signature', $field['type']);
        $this->assertSame('{{signature}}', $field['anchor']);
        $this->assertArrayNotHasKey('page', $field);
    }
}
