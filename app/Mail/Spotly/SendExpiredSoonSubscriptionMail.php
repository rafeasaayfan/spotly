<?php

namespace App\Mail\Spotly;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendExpiredSoonSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $websiteName;
    protected string $ownerName;
    protected string $timeRemaining;

    /**
     * Create a new message instance.
     */
    public function __construct(string $websiteName, string $ownerName, string $timeRemaining)
    {
        $this->websiteName = $websiteName;
        $this->ownerName = $ownerName;
        $this->timeRemaining = $timeRemaining;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Expiring Soon',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.spotly.expiredSoonSubscription',
            with: [
                'websiteName' => $this->websiteName,
                'ownerName' => $this->ownerName,
                'timeRemaining' => $this->timeRemaining,
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
