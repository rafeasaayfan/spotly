<?php

namespace App\Mail\Websites\ecommerce;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $orderNumber;
    public string $action;
    public string $websiteName;
    public string $websiteEmail;
    public string $websiteSubdomain;

    /**
     * Create a new message instance.
     */
    public function __construct(string $orderNumber, string $action, string $websiteName, string $websiteEmail, string $websiteSubdomain)
    {
        $this->orderNumber = $orderNumber;
        $this->action = $action;
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
            subject: 'Order Received - Pending Review',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.websites.ecommerce.orderClientMail',
            with: [
                'orderNumber' => $this->orderNumber,
                'action' => $this->action,
                'websiteName' => $this->websiteName,
                'websiteEmail' => $this->websiteEmail,
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
