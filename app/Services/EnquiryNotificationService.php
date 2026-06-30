<?php

namespace App\Services;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactEnquiryMail;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Mail;

class EnquiryNotificationService
{
    public function __construct(
        private readonly MailchimpService $mailchimp,
    ) {}

    public function sendAfterResponse(Enquiry $enquiry): void
    {
        $enquiryId = $enquiry->id;
        $mailDetails = $enquiry->toMailDetails();
        $contactTo = (string) config('riskwisdom.contact_to_address', config('mail.from.address'));
        $mailchimp = $this->mailchimp;

        dispatch(function () use ($enquiryId, $mailDetails, $contactTo, $mailchimp): void {
            $enquiry = Enquiry::query()->find($enquiryId);

            if ($enquiry === null) {
                return;
            }

            try {
                Mail::to($contactTo)->send(new ContactEnquiryMail($mailDetails));
                $enquiry->update(['email_sent_at' => now()]);
            } catch (\Throwable $exception) {
                report($exception);
            }

            try {
                Mail::to($mailDetails['email'])->send(new ContactAutoReplyMail($mailDetails));
                $enquiry->update(['auto_reply_sent_at' => now()]);
            } catch (\Throwable $exception) {
                report($exception);
            }

            if ($enquiry->marketing_consent) {
                if ($mailchimp->isConfigured()) {
                    try {
                        $mailchimp->subscribeEnquiry($enquiry);
                    } catch (\Throwable $exception) {
                        report($exception);
                        $mailchimp->recordSyncError($enquiry, $exception);
                    }
                }
            }
        })->afterResponse();
    }
}
