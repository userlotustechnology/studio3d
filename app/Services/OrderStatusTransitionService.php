<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderStatusTransitionService
{
    /**
     * Define as transições válidas entre estados
     */
    private const VALID_TRANSITIONS = [
        'draft' => ['pending', 'cancelled'],       // Draft pode ir para pending (finalização) ou cancelado
        'pending' => ['processing', 'cancelled'],  // Pending é após finalização pelo cliente
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => [], // Terminal state
        'cancelled' => [], // Terminal state
    ];

    /**
     * Verifica se uma transição é válida
     */
    public function canTransition(string $fromStatus, string $toStatus): bool
    {
        if ($fromStatus === $toStatus) {
            return false; // Não permitir transição para o mesmo status
        }

        return in_array($toStatus, self::VALID_TRANSITIONS[$fromStatus] ?? []);
    }

    /**
     * Obtém as transições possíveis a partir de um status
     */
    public function getPossibleTransitions(string $status): array
    {
        return self::VALID_TRANSITIONS[$status] ?? [];
    }

    /**
     * Realiza uma transição de status com validação
     * 
     * @throws \InvalidArgumentException Se a transição não for válida
     */
    public function transitionStatus(
        Order $order,
        string $newStatus,
        string $reason = null,
        string $changedBy = 'system'
    ): void {
        $currentStatus = $order->status;

        // Validar transição
        if (!$this->canTransition($currentStatus, $newStatus)) {
            $validTransitions = implode(', ', $this->getPossibleTransitions($currentStatus));
            throw new \InvalidArgumentException(
                "Transição inválida de '{$currentStatus}' para '{$newStatus}'. " .
                "Transições válidas: {$validTransitions}"
            );
        }

        // Registrar histórico
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $currentStatus,
            'to_status' => $newStatus,
            'reason' => $reason,
            'changed_by' => $changedBy,
        ]);

        // Atualizar status do pedido
        $updateData = ['status' => $newStatus];

        // Lógica específica para cada status
        match ($newStatus) {
            'pending' => $updateData['is_draft'] = false,  // Quando vai para pending, não é mais draft
            'processing' => $updateData['paid_at'] = now(),
            'shipped' => $updateData['shipped_at'] = now(),
            'delivered' => $updateData['delivered_at'] = now(),
            default => null,
        };

        $order->update($updateData);

        // Disparar notificação para Slack via webhook
        $this->sendSlackNotification($order, $currentStatus, $newStatus, $reason);
    }

    /**
     * Envia notificação ao Slack via webhook
     */
    private function sendSlackNotification(Order $order, string $previousStatus, string $newStatus, ?string $reason = null): void
    {
        $webhookUrl = config('services.slack.webhook_url');

        if (!$webhookUrl) {
            Log::debug('SLACK_WEBHOOK_URL não configurada');
            return;
        }

        $statusLabels = [
            'draft' => 'Rascunho',
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];

        $statusColors = [
            'draft' => '#9ca3af',        // Cinza
            'pending' => '#f59e0b',      // Amarelo
            'processing' => '#3b82f6',   // Azul
            'shipped' => '#8b5cf6',      // Roxo
            'delivered' => '#10b981',    // Verde
            'cancelled' => '#ef4444',    // Vermelho
        ];

        $previousLabel = $statusLabels[$previousStatus] ?? ucfirst($previousStatus);
        $newLabel = $statusLabels[$newStatus] ?? ucfirst($newStatus);
        $color = $statusColors[$newStatus] ?? '#999999';

        $fields = [
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
                'value' => $order->customer?->name ?? 'N/A',
                'short' => true,
            ],
            [
                'title' => 'Email',
                'value' => $order->customer?->email ?? 'N/A',
                'short' => true,
            ],
            [
                'title' => 'Total',
                'value' => 'R$ ' . number_format($order->total, 2, ',', '.'),
                'short' => true,
            ],
            [
                'title' => 'Data',
                'value' => $order->created_at->format('d/m/Y H:i:s'),
                'short' => true,
            ],
        ];

        if ($reason) {
            $fields[] = [
                'title' => 'Motivo',
                'value' => $reason,
                'short' => false,
            ];
        }

        $items = $order->items->map(fn($item) => "• {$item->product->name} (Qtd: {$item->quantity})")->join("\n");
        $fields[] = [
            'title' => 'Itens',
            'value' => $items ?: "Sem itens",
            'short' => false,
        ];

        $payload = [
            'username' => 'Studio3D Bot',
            'icon_emoji' => ':package:',
            'attachments' => [
                [
                    'color' => $color,
                    'title' => '📦 Atualização de Pedido #' . $order->order_number,
                    'fields' => $fields,
                    'footer' => 'Studio3D - E-commerce',
                    'ts' => time(),
                ]
            ]
        ];

        try {
            Http::timeout(10)->post($webhookUrl, $payload);
            Log::info('Notificação Slack enviada para pedido: ' . $order->order_number);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação Slack: ' . $e->getMessage());
        }
    }

    /**
     * Obtém as transições permitidas com labels traduzidos
     */
    public function getPossibleTransitionsLabeled(string $status): array
    {
        $possible = $this->getPossibleTransitions($status);
        
        $labels = [
            'draft' => 'Rascunho',
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];

        return array_map(fn($s) => [
            'status' => $s,
            'label' => $labels[$s] ?? $s
        ], $possible);
    }

    /**
     * Obtém o histórico formatado de um pedido
     */
    public function getFormattedHistory(Order $order): array
    {
        $histories = $order->statusHistories()->orderBy('created_at', 'asc')->get();
        
        $formatted = [];
        foreach ($histories as $history) {
            $formatted[] = [
                'from_status' => $history->from_status,
                'to_status' => $history->to_status,
                'from_label' => OrderStatusHistory::translateStatus($history->from_status),
                'to_label' => OrderStatusHistory::translateStatus($history->to_status),
                'icon' => OrderStatusHistory::statusIcon($history->to_status),
                'color' => OrderStatusHistory::statusColor($history->to_status),
                'reason' => $history->reason,
                'changed_by' => $history->changed_by,
                'created_at' => $history->created_at,
                'created_at_formatted' => $history->created_at->format('d/m/Y \à\s H:i:s'),
            ];
        }

        return $formatted;
    }
}
