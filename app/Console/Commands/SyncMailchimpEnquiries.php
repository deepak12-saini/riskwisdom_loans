<?php

namespace App\Console\Commands;

use App\Models\Enquiry;
use App\Services\MailchimpService;
use Illuminate\Console\Command;

class SyncMailchimpEnquiries extends Command
{
    protected $signature = 'mailchimp:sync-enquiries
                            {--dry-run : List enquiries that would sync without calling Mailchimp}';

    protected $description = 'Sync opted-in enquiries to Mailchimp that have not been synced yet';

    public function handle(MailchimpService $mailchimp): int
    {
        if (! $this->option('dry-run') && ! $mailchimp->isConfigured()) {
            $this->error('Mailchimp is not configured. Set MAILCHIMP_* in .env or use --dry-run.');

            return self::FAILURE;
        }

        $query = Enquiry::query()
            ->where('marketing_consent', true)
            ->whereNull('mailchimp_synced_at')
            ->orderBy('id');

        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info('No enquiries to sync.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("Would sync {$count} enquiry/enquiries:");

            $query->each(function (Enquiry $enquiry): void {
                $this->line("  #{$enquiry->id} {$enquiry->email} ({$enquiry->lead_type})");
            });

            return self::SUCCESS;
        }

        $synced = 0;
        $failed = 0;

        $query->each(function (Enquiry $enquiry) use ($mailchimp, &$synced, &$failed): void {
            try {
                $mailchimp->subscribeEnquiry($enquiry);
                $synced++;
                $this->line("Synced #{$enquiry->id} {$enquiry->email}");
            } catch (\Throwable $exception) {
                $failed++;
                report($exception);
                $mailchimp->recordSyncError($enquiry, $exception);
                $this->error("Failed #{$enquiry->id} {$enquiry->email}: {$exception->getMessage()}");
            }
        });

        $this->info("Done. Synced: {$synced}, failed: {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
