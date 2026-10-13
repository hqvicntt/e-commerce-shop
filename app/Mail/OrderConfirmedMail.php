<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    // Public variable to hold the order data structure inside blade templates
    public Order $order;

    /**
     * Create a new message instance.
     * 
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope containing the mail subject header parameters.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Shop - Order Confirmation #' . $this->order->id,
        );
    }

    /**
     * Get the message content definition pointing to the designated template layout.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmed', // Links to the blade template we will create
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
