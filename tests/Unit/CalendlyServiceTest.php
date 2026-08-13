<?php

namespace Tests\Unit;

use App\Models\Enquiry;
use App\Services\CalendlyService;
use App\Services\EnquiryNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CalendlyServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_signature_accepts_valid_hmac(): void
    {
        config([
            'calendly.webhook_signing_key' => 'test-signing-key',
            'calendly.webhook_tolerance_seconds' => 300,
        ]);

        $payload = '{"event":"invitee.created"}';
        $timestamp = (string) time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'test-signing-key');
        $header = "t={$timestamp},v1={$signature}";

        $service = app(CalendlyService::class);

        $this->assertTrue($service->verifyWebhookSignature($payload, $header));
    }

    public function test_webhook_signature_rejects_invalid_hmac(): void
    {
        config([
            'calendly.webhook_signing_key' => 'test-signing-key',
            'calendly.webhook_tolerance_seconds' => 300,
        ]);

        $payload = '{"event":"invitee.created"}';
        $timestamp = (string) time();
        $header = "t={$timestamp},v1=not-valid";

        $service = app(CalendlyService::class);

        $this->assertFalse($service->verifyWebhookSignature($payload, $header));
    }

    public function test_invitee_created_stores_enquiry(): void
    {
        $notifications = Mockery::mock(EnquiryNotificationService::class);
        $notifications->shouldReceive('sendAfterResponse')->once();

        $this->app->instance(EnquiryNotificationService::class, $notifications);

        $service = app(CalendlyService::class);

        $service->handleWebhookPayload([
            'event' => 'invitee.created',
            'payload' => [
                'uri' => 'https://api.calendly.com/scheduled_events/AAA/invitees/BBB',
                'email' => 'sam@example.com',
                'name' => 'Sam Tester',
                'first_name' => 'Sam',
                'last_name' => 'Tester',
                'timezone' => 'Australia/Sydney',
                'cancel_url' => 'https://calendly.com/cancellations/x',
                'reschedule_url' => 'https://calendly.com/reschedulings/x',
                'event' => 'https://api.calendly.com/scheduled_events/AAA',
                'questions_and_answers' => [
                    ['question' => 'Phone Number', 'answer' => '0412 345 678', 'position' => 0],
                ],
                'scheduled_event' => [
                    'uri' => 'https://api.calendly.com/scheduled_events/AAA',
                    'name' => '15 Minute Meeting',
                    'start_time' => '2026-08-12T02:00:00.000000Z',
                    'end_time' => '2026-08-12T02:15:00.000000Z',
                ],
            ],
        ]);

        $enquiry = Enquiry::query()->where('lead_type', 'calendly')->first();

        $this->assertNotNull($enquiry);
        $this->assertSame('Sam', $enquiry->first_name);
        $this->assertSame('Tester', $enquiry->last_name);
        $this->assertSame('sam@example.com', $enquiry->email);
        $this->assertSame('0412 345 678', $enquiry->phone);
        $this->assertSame('ready_now', $enquiry->timeline);
        $this->assertSame('calendly', $enquiry->source);
        $this->assertSame(
            'https://api.calendly.com/scheduled_events/AAA/invitees/BBB',
            $enquiry->metadata['calendly_invitee_uri'] ?? null
        );
        $this->assertStringContainsString('15 Minute Meeting', $enquiry->enquiry);
    }

    public function test_duplicate_invitee_created_is_ignored(): void
    {
        $notifications = Mockery::mock(EnquiryNotificationService::class);
        $notifications->shouldReceive('sendAfterResponse')->once();

        $this->app->instance(EnquiryNotificationService::class, $notifications);

        $service = app(CalendlyService::class);
        $payload = [
            'event' => 'invitee.created',
            'payload' => [
                'uri' => 'https://api.calendly.com/scheduled_events/AAA/invitees/DUP',
                'email' => 'dup@example.com',
                'name' => 'Dup User',
                'scheduled_event' => [
                    'name' => 'Call',
                    'start_time' => '2026-08-12T02:00:00.000000Z',
                    'end_time' => '2026-08-12T02:15:00.000000Z',
                ],
            ],
        ];

        $service->handleWebhookPayload($payload);
        $service->handleWebhookPayload($payload);

        $this->assertSame(1, Enquiry::query()->where('lead_type', 'calendly')->count());
    }

    public function test_invitee_canceled_marks_enquiry(): void
    {
        $enquiry = Enquiry::query()->create([
            'lead_type' => 'calendly',
            'first_name' => 'Sam',
            'last_name' => 'Tester',
            'phone' => '0412345678',
            'email' => 'sam@example.com',
            'enquiry' => 'Booked',
            'source' => 'calendly',
            'timeline' => 'ready_now',
            'status' => 'new',
            'metadata' => [
                'calendly_invitee_uri' => 'https://api.calendly.com/scheduled_events/AAA/invitees/CCC',
                'calendly_status' => 'active',
            ],
        ]);

        $service = app(CalendlyService::class);
        $service->handleWebhookPayload([
            'event' => 'invitee.canceled',
            'payload' => [
                'uri' => 'https://api.calendly.com/scheduled_events/AAA/invitees/CCC',
                'cancellation' => ['reason' => 'Changed plans'],
            ],
        ]);

        $enquiry->refresh();

        $this->assertSame('canceled', $enquiry->status);
        $this->assertSame('canceled', $enquiry->metadata['calendly_status'] ?? null);
        $this->assertStringContainsString('[Canceled]', $enquiry->enquiry);
        $this->assertDatabaseHas('enquiry_activities', [
            'enquiry_id' => $enquiry->id,
            'type' => 'calendly_canceled',
        ]);
    }
}
