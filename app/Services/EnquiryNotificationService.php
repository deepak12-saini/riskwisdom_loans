<?php

namespace App\Services;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactEnquiryMail;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Mail;

class EnquiryNotificationService
{
    public function sendAfterResponse(Enquiry $enquiry): void
    {
        $enquiryId = $enquiry->id;
        $mailDetails = $enquiry->toMailDetails();
        $contactTo = (string) env('CONTACT_TO_ADDRESS', (string) config('mail.from.address'));

        dispatch(function () use ($enquiryId, $mailDetails, $contactTo): void {
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
        })->afterResponse();
    }
}
