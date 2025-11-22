<?php

namespace App\Mail\Spotly;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $ownerName;
    protected string $websiteName;
    protected string $websiteSubdomain;
    protected string $key;
    protected int $status;

    /**
     * Create a new message instance.
     */
    public function __construct($ownerName, $websiteName, $websiteSubdomain, $key, $status)
    {
        $this->ownerName = $ownerName;
        $this->websiteName = $websiteName;
        $this->websiteSubdomain = $websiteSubdomain;
        $this->key = $key;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Website Status Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.spotly.websiteStatus',
            with: [
                'ownerName' => $this->ownerName,
                'websiteName' => $this->websiteName,
                'websiteSubdomain' => $this->websiteSubdomain,
                'status' => $this->status,
                'key' => $this->key,
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
