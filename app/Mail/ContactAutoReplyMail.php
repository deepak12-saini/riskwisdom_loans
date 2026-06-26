<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReplyMail extends Mailable
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
        return new Envelope(
            subject: 'We received your enquiry – Riskwisdom Loans',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-auto-reply',
            with: [
                'details' => $this->details,
            ],
        );
    }
}
