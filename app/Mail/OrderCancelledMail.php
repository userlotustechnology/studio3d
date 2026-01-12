<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order,
        public ?string $reason = null
    ) {
        // Configurar para envio na fila com delay de 1 minuto
        $this->queue = 'default';
        $this->delay = now()->addMinute();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->order->customer?->email ?? null,
            subject: 'Seu Pedido #' . $this->order->order_number . ' foi Cancelado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-cancelled',
            with: [
                'order' => $this->order,
                'reason' => $this->reason,
                'storeName' => \App\Models\Setting::get('store_name', 'Studio3D'),
                'supportEmail' => \App\Models\Setting::get('support_email', 'suporte@exemplo.com'),
            ],
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
