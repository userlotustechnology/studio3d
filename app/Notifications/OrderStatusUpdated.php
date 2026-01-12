<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        return ['slack_webhook'];
    }

    /**
     * Send the notification via Slack webhook.
     */
    public function toSlackWebhook(object $notifiable): void
    {
        $webhookUrl = config('services.slack.webhook_url');

        if (!$webhookUrl) {
            Log::warning('Slack webhook URL not configured');
            return;
        }

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
        $color = $statusColors[$this->newStatus] ?? '#999999';

        $payload = [
            'username' => 'Studio3D Bot',
            'icon_emoji' => ':package:',
            'attachments' => [
                [
                    'color' => $color,
                    'title' => '📦 Atualização de Pedido #' . $this->order->order_number,
                    'fields' => [
                        [
                            'title' => 'Status Anterior',
                            'value' => $previousLabel,
                            'short' => true,
                        ],
                        [
                            'title' => 'Novo Status',
                            'value' => $newLabel,
                            'short' => true,
                        ],
                        [
                            'title' => 'Cliente',
                            'value' => $this->order->customer?->name ?? 'N/A',
                            'short' => true,
                        ],
                        [
                            'title' => 'Email',
                            'value' => $this->order->customer?->email ?? 'N/A',
                            'short' => true,
                        ],
                        [
                            'title' => 'Total',
                            'value' => 'R$ ' . number_format($this->order->total, 2, ',', '.'),
                            'short' => true,
                        ],
                        [
                            'title' => 'Data',
                            'value' => $this->order->created_at->format('d/m/Y H:i:s'),
                            'short' => true,
                        ],
                    ],
                    'footer' => 'Studio3D - E-commerce',
                    'ts' => time(),
                ]
            ]
        ];

        if ($this->reason) {
            $payload['attachments'][0]['fields'][] = [
                'title' => 'Motivo',
                'value' => $this->reason,
                'short' => false,
            ];
        }

        $payload['attachments'][0]['fields'][] = [
            'title' => 'Itens',
            'value' => $this->formatItems(),
            'short' => false,
        ];

        try {
            Http::post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('Falha ao enviar notificação para Slack: ' . $e->getMessage());
        }
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

