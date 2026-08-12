<?php

namespace App\Services;

use App\Models\Enquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CalendlyService
{
    public function __construct(
        private readonly EnquiryNotificationService $notifications,
    ) {}

    public function verifyWebhookSignature(string $payload, ?string $signatureHeader): bool
    {
        $signingKey = trim((string) config('calendly.webhook_signing_key', ''));

        if ($signingKey === '' || $signatureHeader === null || trim($signatureHeader) === '') {
            Log::warning('Calendly webhook rejected: missing signing key or signature header');

            return false;
        }

        $parts = $this->parseSignatureHeader($signatureHeader);
        $timestamp = $parts['t'] ?? null;
        $signature = $parts['v1'] ?? null;

        if ($timestamp === null || $signature === null) {
            Log::warning('Calendly webhook rejected: malformed signature header');

            return false;
        }

        $tolerance = max(60, (int) config('calendly.webhook_tolerance_seconds', 180));

        if (abs(time() - (int) $timestamp) > $tolerance) {
            Log::warning('Calendly webhook rejected: stale timestamp', [
                'timestamp' => $timestamp,
            ]);

            return false;
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $signingKey);

        return hash_equals($expected, $signature);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function handleWebhookPayload(array $data): void
    {
        $event = (string) ($data['event'] ?? '');

        if ($event === 'invitee.created') {
            $this->handleInviteeCreated($data);

            return;
        }

        if ($event === 'invitee.canceled') {
            $this->handleInviteeCanceled($data);

            return;
        }

        Log::info('Calendly webhook ignored', ['event' => $event ?: 'unknown']);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function handleInviteeCreated(array $data): void
    {
        $payload = is_array($data['payload'] ?? null) ? $data['payload'] : [];
        $inviteeUri = trim((string) ($payload['uri'] ?? ''));

        if ($inviteeUri === '') {
            Log::warning('Calendly invitee.created missing invitee uri');

            return;
        }

        $existing = Enquiry::query()
            ->where('lead_type', 'calendly')
            ->where('metadata->calendly_invitee_uri', $inviteeUri)
            ->first();

        if ($existing !== null) {
            Log::info('Calendly invitee.created already stored', ['enquiry_id' => $existing->id]);

            return;
        }

        [$firstName, $lastName] = $this->splitName($payload);
        $email = trim((string) ($payload['email'] ?? ''));
        $phone = $this->extractPhone($payload);
        $scheduled = is_array($payload['scheduled_event'] ?? null) ? $payload['scheduled_event'] : [];
        $startTime = (string) ($scheduled['start_time'] ?? '');
        $endTime = (string) ($scheduled['end_time'] ?? '');
        $eventName = (string) ($scheduled['name'] ?? 'Calendly booking');
        $timezone = (string) ($payload['timezone'] ?? '');
        $questions = $this->formatQuestions($payload);

        $enquiryText = $this->buildEnquiryMessage(
            eventName: $eventName,
            startTime: $startTime,
            endTime: $endTime,
            timezone: $timezone,
            questions: $questions,
        );

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'calendly',
            'first_name' => $firstName !== '' ? $firstName : 'Calendly',
            'last_name' => $lastName !== '' ? $lastName : 'Lead',
            'phone' => $phone !== '' ? $phone : 'Not provided',
            'email' => $email !== '' ? $email : 'unknown@example.com',
            'loan_type' => null,
            'timeline' => 'ready_now',
            'state' => null,
            'enquiry' => $enquiryText,
            'source' => 'calendly',
            'utm_source' => null,
            'utm_medium' => null,
            'utm_campaign' => null,
            'ip_address' => null,
            'status' => 'new',
            'call_status' => 'booked',
            'marketing_consent' => false,
            'metadata' => [
                'calendly_invitee_uri' => $inviteeUri,
                'calendly_event_uri' => (string) ($payload['event'] ?? ($scheduled['uri'] ?? '')),
                'calendly_event_name' => $eventName,
                'calendly_start_time' => $startTime,
                'calendly_end_time' => $endTime,
                'calendly_timezone' => $timezone,
                'calendly_cancel_url' => (string) ($payload['cancel_url'] ?? ''),
                'calendly_reschedule_url' => (string) ($payload['reschedule_url'] ?? ''),
                'calendly_status' => 'active',
                'calendly_questions' => is_array($payload['questions_and_answers'] ?? null)
                    ? $payload['questions_and_answers']
                    : [],
            ],
        ]);

        $this->notifications->sendAfterResponse($enquiry);

        Log::info('Calendly booking stored as enquiry', [
            'enquiry_id' => $enquiry->id,
            'invitee_uri' => $inviteeUri,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function handleInviteeCanceled(array $data): void
    {
        $payload = is_array($data['payload'] ?? null) ? $data['payload'] : [];
        $inviteeUri = trim((string) ($payload['uri'] ?? ''));

        if ($inviteeUri === '') {
            return;
        }

        $enquiry = Enquiry::query()
            ->where('lead_type', 'calendly')
            ->where('metadata->calendly_invitee_uri', $inviteeUri)
            ->first();

        if ($enquiry === null) {
            Log::info('Calendly invitee.canceled: no matching enquiry', [
                'invitee_uri' => $inviteeUri,
            ]);

            return;
        }

        $metadata = is_array($enquiry->metadata) ? $enquiry->metadata : [];
        $metadata['calendly_status'] = 'canceled';
        $metadata['calendly_canceled_at'] = now()->toIso8601String();
        $metadata['calendly_cancel_reason'] = (string) (
            $payload['cancellation']['reason']
            ?? $payload['cancel_reason']
            ?? ''
        );

        $enquiry->update([
            'status' => 'canceled',
            'metadata' => $metadata,
            'enquiry' => trim($enquiry->enquiry."\n\n[Canceled] This Calendly booking was canceled."),
        ]);

        Log::info('Calendly booking marked canceled', [
            'enquiry_id' => $enquiry->id,
        ]);
    }

    /**
     * @return array{t?: string, v1?: string}
     */
    private function parseSignatureHeader(string $header): array
    {
        $parts = [];

        foreach (explode(',', $header) as $segment) {
            $segment = trim($segment);
            if (! str_contains($segment, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $segment, 2);
            $parts[trim($key)] = trim($value);
        }

        return $parts;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{0: string, 1: string}
     */
    private function splitName(array $payload): array
    {
        $first = trim((string) ($payload['first_name'] ?? ''));
        $last = trim((string) ($payload['last_name'] ?? ''));

        if ($first !== '' || $last !== '') {
            return [$first, $last];
        }

        $full = trim((string) ($payload['name'] ?? ''));

        if ($full === '') {
            return ['', ''];
        }

        $parts = preg_split('/\s+/', $full) ?: [];
        $firstName = (string) array_shift($parts);
        $lastName = trim(implode(' ', $parts));

        return [$firstName, $lastName];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function extractPhone(array $payload): string
    {
        $reminder = trim((string) ($payload['text_reminder_number'] ?? ''));
        if ($reminder !== '') {
            return $reminder;
        }

        $questions = is_array($payload['questions_and_answers'] ?? null)
            ? $payload['questions_and_answers']
            : [];

        foreach ($questions as $item) {
            if (! is_array($item)) {
                continue;
            }

            $question = Str::lower((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($answer === '') {
                continue;
            }

            if (str_contains($question, 'phone')
                || str_contains($question, 'mobile')
                || str_contains($question, 'cell')
                || str_contains($question, 'contact number')
            ) {
                return $answer;
            }
        }

        return '';
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function formatQuestions(array $payload): string
    {
        $questions = is_array($payload['questions_and_answers'] ?? null)
            ? $payload['questions_and_answers']
            : [];

        $lines = [];

        foreach ($questions as $item) {
            if (! is_array($item)) {
                continue;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' && $answer === '') {
                continue;
            }

            $lines[] = ($question !== '' ? $question : 'Answer').': '.$answer;
        }

        return implode("\n", $lines);
    }

    private function buildEnquiryMessage(
        string $eventName,
        string $startTime,
        string $endTime,
        string $timezone,
        string $questions,
    ): string {
        $lines = [
            'Calendly booking confirmed.',
            'Event: '.($eventName !== '' ? $eventName : 'Book a call'),
        ];

        if ($startTime !== '') {
            $lines[] = 'Starts: '.$this->formatBookingTime($startTime, $timezone);
        }

        if ($endTime !== '') {
            $lines[] = 'Ends: '.$this->formatBookingTime($endTime, $timezone);
        }

        if ($timezone !== '') {
            $lines[] = 'Invitee timezone: '.$timezone;
        }

        if ($questions !== '') {
            $lines[] = '';
            $lines[] = 'Booking answers:';
            $lines[] = $questions;
        }

        $lines[] = '';
        $lines[] = 'Staff: call the contact at the booked time (also shown in Calendly / calendar).';

        return implode("\n", $lines);
    }

    private function formatBookingTime(string $iso, string $timezone): string
    {
        try {
            $dt = new \DateTimeImmutable($iso);
            if ($timezone !== '') {
                $dt = $dt->setTimezone(new \DateTimeZone($timezone));
            } else {
                $dt = $dt->setTimezone(new \DateTimeZone('Australia/Sydney'));
            }

            return $dt->format('D j M Y g:ia T');
        } catch (\Throwable) {
            return $iso;
        }
    }
}
