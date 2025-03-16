<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use PDF; // Use the alias for the PDF facade

class SendOrderStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $isUpdate;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param bool $isUpdate  Indicates whether this is an update email.
     * @param string $pdf  The PDF content.
     */
    public function __construct(Order $order, bool $isUpdate = false, string $pdf)
    {
        $this->order = $order;
        $this->isUpdate = $isUpdate;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        // Retrieve the customer's email; if not set, fall back to the related user’s email.
        $customerEmail = $this->order->customer->email ?: $this->order->customer->user->email;

        // Set subject based on whether this is an update or a new confirmation.
        $subject = $this->isUpdate ? 'Order Update' : 'Order Confirmation';

        return new Envelope(
            from: new Address('noreply@homeessence', 'HomeEssence'),
            to: [new Address($customerEmail, $this->order->customer->fname . ' ' . $this->order->customer->lname)],
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        $view = $this->isUpdate ? 'email.order_update' : 'email.order_status';

        return new Content(
            view: $view,
            with: [
                'order'    => $this->order,
                'isUpdate' => $this->isUpdate,
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
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn() => $this->pdf,
                'receipt.pdf',
                ['mime' => 'application/pdf']
            ),
        ];
    }
}