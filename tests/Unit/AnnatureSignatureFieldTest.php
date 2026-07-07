<?php

namespace Tests\Unit;

use App\Services\AnnatureService;
use App\Support\PdfSignaturePlacement;
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

    public function test_signature_field_uses_pdf_aware_coordinates_by_default(): void
    {
        config([
            'annature.signature_placement' => 'coordinates',
            'annature.signature_field' => [
                'margin_x' => 72,
                'margin_y' => 72,
                'width' => 200,
                'height' => 50,
            ],
        ]);

        $pdf = "%PDF-1.4\n2 0 obj<</Type/Page/MediaBox[0 0 595 500]>>endobj\n";
        $field = app(AnnatureService::class)->signatureFieldFor('privacy_consent', $pdf);

        $this->assertSame('signature', $field['type']);
        $this->assertTrue($field['required']);
        $this->assertSame(1, $field['page']);
        $this->assertLessThan(500, $field['y_coordinate'] + $field['height']);
        $this->assertArrayNotHasKey('anchor', $field);
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

    public function test_signature_field_auto_uses_anchor_when_marker_exists_in_pdf(): void
    {
        config([
            'annature.signature_placement' => 'coordinates',
            'annature.anchor' => '{{signature}}',
        ]);

        $pdf = "%PDF-1.4\n{{signature}}\n2 0 obj<</Type/Page/MediaBox[0 0 595 842]>>endobj\n";
        $field = app(AnnatureService::class)->signatureFieldFor('other', $pdf);

        $this->assertSame('{{signature}}', $field['anchor']);
    }
}
