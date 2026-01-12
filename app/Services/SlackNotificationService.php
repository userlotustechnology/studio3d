<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class SlackNotificationService
{
    private Client $client;
    private string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.slack.webhook_url') ?? env('SLACK_WEBHOOK_URL');
        $this->client = new Client();
    }

    /**
     * Send a notification to Slack
     */
    public function send(string $title, string $message, array $fields = [], ?string $color = null): bool
    {
        if (!$this->webhookUrl) {
            Log::warning('Slack webhook URL not configured');
            return false;
        }

        try {
            $payload = [
                'attachments' => [
                    [
                        'title' => $title,
                        'text' => $message,
                        'color' => $color ?? '#36a64f',
                        'mrkdwn_in' => ['text', 'pretext'],
                        'fields' => $fields,
                        'footer' => config('app.name'),
                        'ts' => time(),
                    ]
                ]
            ];

            $this->client->post($this->webhookUrl, [
                'json' => $payload,
                'timeout' => 10,
            ]);

            return true;
        } catch (GuzzleException $e) {
            Log::error('Failed to send Slack notification', [
                'message' => $e->getMessage(),
                'title' => $title,
            ]);
            return false;
        }
    }

    /**
     * Send order created notification
     */
    public function notifyOrderCreated($order): bool
    {
        $customer = $order->customer;
        
        $fields = [
            [
                'title' => 'Pedido #',
                'value' => $order->order_number,
                'short' => true,
            ],
            [
                'title' => 'Cliente',
                'value' => $customer?->name ?? 'Desconhecido',
                'short' => true,
            ],
            [
                'title' => 'Total',
                'value' => 'R$ ' . number_format($order->total, 2, ',', '.'),
                'short' => true,
            ],
            [
                'title' => 'Status',
                'value' => $this->getOrderStatusLabel($order->status),
                'short' => true,
            ],
        ];

        return $this->send(
            '🎉 Novo Pedido Realizado',
            sprintf(
                'Um novo pedido foi recebido de *%s* com valor total de *R$ %s*',
                $customer?->name ?? 'Cliente',
                number_format($order->total, 2, ',', '.')
            ),
            $fields,
            '#36a64f'
        );
    }

    /**
     * Get order status label in Portuguese
     */
    private function getOrderStatusLabel(string $status): string
    {
        $statuses = [
            'pending' => '⏳ Pendente',
            'confirmed' => '✅ Confirmado',
            'processing' => '⚙️ Processando',
            'shipped' => '📦 Enviado',
            'delivered' => '✔️ Entregue',
            'cancelled' => '❌ Cancelado',
        ];

        return $statuses[$status] ?? ucfirst($status);
    }
}
