<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEnquiryMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param array<string, string> $details
     */
    public function __construct(public array $details)
    {
    }

    public function envelope(): Envelope
    {
        $fullName = trim($this->details['first_name'].' '.$this->details['last_name']);

        return new Envelope(
            subject: 'New Riskwisdom Loans enquiry from '.$fullName,
            replyTo: [
                new Address($this->details['email'], $fullName),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-enquiry',
            with: [
                'details' => $this->details,
            ],
        );
    }
}
