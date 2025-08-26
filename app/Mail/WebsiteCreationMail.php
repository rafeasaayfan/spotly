<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $recipientType;
    protected string $websiteName;
    protected string $ownerEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($recipientType = 'admin' | 'owner', $websiteName, $ownerEmail = '')
    {
        $this->recipientType = $recipientType;
        $this->websiteName = $websiteName;
        $this->ownerEmail = $ownerEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Website Creation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.websiteCreation',
            with: [
                'recipientType' => $this->recipientType,
                'websiteName' => $this->websiteName,
                'ownerEmail' => $this->ownerEmail,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
