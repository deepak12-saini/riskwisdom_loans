<?php

namespace App\Services;

use App\Models\Enquiry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MailchimpService
{
    public function isConfigured(): bool
    {
        if (! config('mailchimp.enabled')) {
            return false;
        }

        return filled(config('mailchimp.api_key'))
            && filled(config('mailchimp.audience_id'))
            && filled(config('mailchimp.server_prefix'));
    }

    public function subscribeEnquiry(Enquiry $enquiry): void
    {
        if (! $enquiry->marketing_consent) {
            return;
        }

        $this->ensureConfigured();

        $email = strtolower(trim($enquiry->email));
        $subscriberHash = $this->subscriberHash($email);

        $response = $this->api()->put(
            '/lists/'.config('mailchimp.audience_id').'/members/'.$subscriberHash,
            [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $enquiry->first_name,
                    'LNAME' => $enquiry->last_name,
                    'PHONE' => $enquiry->phone,
                ],
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'Mailchimp member upsert failed: '.$response->body()
            );
        }

        $tags = $this->tagsForEnquiry($enquiry);

        if ($tags !== []) {
            $tagResponse = $this->api()->post(
                '/lists/'.config('mailchimp.audience_id').'/members/'.$subscriberHash.'/tags',
                [
                    'tags' => array_map(
                        fn (string $name): array => ['name' => $name, 'status' => 'active'],
                        $tags
                    ),
                ]
            );

            if ($tagResponse->failed()) {
                throw new RuntimeException(
                    'Mailchimp tag update failed: '.$tagResponse->body()
                );
            }
        }

        $enquiry->update([
            'mailchimp_synced_at' => now(),
            'mailchimp_sync_error' => null,
        ]);
    }

    /**
     * @param  list<string>  $tags
     */
    public function addTags(string $email, array $tags): void
    {
        $this->ensureConfigured();

        $subscriberHash = $this->subscriberHash($email);

        $response = $this->api()->post(
            '/lists/'.config('mailchimp.audience_id').'/members/'.$subscriberHash.'/tags',
            [
                'tags' => array_map(
                    fn (string $name): array => ['name' => $name, 'status' => 'active'],
                    $tags
                ),
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'Mailchimp tag update failed: '.$response->body()
            );
        }
    }

    public function recordSyncError(Enquiry $enquiry, \Throwable $exception): void
    {
        $enquiry->update([
            'mailchimp_sync_error' => Str::limit($exception->getMessage(), 1000),
        ]);
    }

    /**
     * @return list<string>
     */
    public function tagsForEnquiry(Enquiry $enquiry): array
    {
        $tags = ['website-lead'];

        if (filled($enquiry->lead_type)) {
            $tags[] = $enquiry->lead_type;
        }

        if (filled($enquiry->loan_type)) {
            $tags[] = $enquiry->loan_type;
        }

        if (filled($enquiry->timeline)) {
            $tags[] = $enquiry->timeline;
        }

        if (filled($enquiry->state)) {
            $tags[] = $enquiry->state;
        }

        if (filled($enquiry->utm_campaign)) {
            $tags[] = 'utm-'.Str::slug($enquiry->utm_campaign, '_');
        }

        return array_values(array_unique($tags));
    }

    protected function subscriberHash(string $email): string
    {
        return md5(strtolower(trim($email)));
    }

    protected function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Mailchimp is not configured.');
        }
    }

    protected function api(): \Illuminate\Http\Client\PendingRequest
    {
        $prefix = (string) config('mailchimp.server_prefix');

        return Http::baseUrl('https://'.$prefix.'.api.mailchimp.com/3.0')
            ->withBasicAuth('mailchimp', (string) config('mailchimp.api_key'))
            ->acceptJson()
            ->asJson();
    }
}
