<?php

namespace App\Mail\Websites\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $websiteEmail;
    protected string $websiteName;
    protected string $websiteSubdomain;
    protected string $websiteType;
    protected string $userName;
    protected string $status;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $websiteEmail,
        string $websiteName,
        string $websiteSubdomain,
        string $websiteType,
        string $userName,
        string $status
    ) {
        $this->websiteEmail = $websiteEmail;
        $this->websiteName = $websiteName;
        $this->websiteSubdomain = $websiteSubdomain;
        $this->websiteType = $websiteType;
        $this->userName = $userName;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->websiteEmail, $this->websiteName),
            subject: 'User Status Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.websites.' . $this->websiteType . '.userStatus',
            with: [
                'websiteName' => $this->websiteName,
                'websiteSubdomain' => $this->websiteSubdomain,
                'userName' => $this->userName,
                'status' => $this->status,
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
