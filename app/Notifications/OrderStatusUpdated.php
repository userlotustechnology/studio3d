<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
        public string $previousStatus,
        public string $newStatus,
        public ?string $reason = null
    ) {
        $this->queue = 'default';
        $this->delay = now()->addSeconds(5);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        $statusLabels = [
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];

        $statusColors = [
            'pending' => '#f59e0b',      // Amarelo
            'processing' => '#3b82f6',   // Azul
            'shipped' => '#8b5cf6',      // Roxo
            'delivered' => '#10b981',    // Verde
            'cancelled' => '#ef4444',    // Vermelho
        ];

        $previousLabel = $statusLabels[$this->previousStatus] ?? ucfirst($this->previousStatus);
        $newLabel = $statusLabels[$this->newStatus] ?? ucfirst($this->newStatus);
        $color = $statusColors[$this->newStatus] ?? '#gray';

        $message = (new SlackMessage)
            ->color($color)
            ->from('Studio3D Bot')
            ->title("📦 Atualização de Pedido #" . $this->order->order_number)
            ->attachment(function ($attachment) use ($previousLabel, $newLabel) {
                $attachment
                    ->field('Status Anterior', $previousLabel, true)
                    ->field('Novo Status', $newLabel, true)
                    ->field('Cliente', $this->order->customer?->name ?? 'N/A', true)
                    ->field('Email', $this->order->customer?->email ?? 'N/A', true)
                    ->field('Total', 'R$ ' . number_format($this->order->total, 2, ',', '.'), true)
                    ->field('Data', $this->order->created_at->format('d/m/Y H:i:s'), true);

                if ($this->reason) {
                    $attachment->field('Motivo', $this->reason, false);
                }

                $attachment->field('Itens', $this->formatItems(), false);
                $attachment->footer('Studio3D - E-commerce');
                $attachment->timestamp();
            });

        return $message;
    }

    /**
     * Formata os itens do pedido para exibir no Slack
     */
    private function formatItems(): string
    {
        $items = $this->order->items->map(function ($item) {
            return "• {$item->product->name} (Qtd: {$item->quantity})";
        })->join("\n");

        return $items ?: "Sem itens";
    }
}
