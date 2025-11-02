<?php

namespace App\Mail\Websites\ecommerce;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $orderNumber;
    public string $websiteName;
    public string $websiteEmail;
    protected string $websiteSubdomain;

    /**
     * Create a new message instance.
     */
    public function __construct(string $orderNumber, string $websiteName, string $websiteEmail, string $websiteSubdomain)
    {
        $this->orderNumber = $orderNumber;
        $this->websiteName = $websiteName;
        $this->websiteEmail = $websiteEmail;
        $this->websiteSubdomain = $websiteSubdomain;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->websiteEmail, $this->websiteName),
            subject: 'New Order Notification (Admin)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.websites.ecommerce.orderAdminMail',
            with: [
                'orderNumber' => $this->orderNumber,
                'websiteName' => $this->websiteName,
                'websiteSubdomain' => $this->websiteSubdomain,
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
