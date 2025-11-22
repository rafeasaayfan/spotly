<?php

namespace App\Mail\Spotly;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendExpiredSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $websiteName;
    protected string $ownerName;
    protected string $ownerEmail;
    protected string $endDate;
    protected bool $isAdmin;

    /**
     * Create a new message instance.
     */
    public function __construct(string $websiteName, string $ownerName, string $ownerEmail, string $endDate, bool $isAdmin)
    {
        $this->websiteName = $websiteName;
        $this->ownerName = $ownerName;
        $this->ownerEmail = $ownerEmail;
        $this->endDate = $endDate;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Expired',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.spotly.expiredSubscription',
            with: [
                'websiteName' => $this->websiteName,
                'ownerName' => $this->ownerName,
                'ownerEmail' => $this->ownerEmail,
                'endDate' => $this->endDate,
                'isAdmin' => $this->isAdmin,
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
