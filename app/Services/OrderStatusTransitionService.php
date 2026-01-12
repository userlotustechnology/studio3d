<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Notification;

class OrderStatusTransitionService
{
    /**
     * Define as transições válidas entre estados
     */
    private const VALID_TRANSITIONS = [
        'pending' => ['processing', 'cancelled'],
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

        // Registrar timestamps para estados específicos
        match ($newStatus) {
            'processing' => $updateData['paid_at'] = now(),
            'shipped' => $updateData['shipped_at'] = now(),
            'delivered' => $updateData['delivered_at'] = now(),
            default => null,
        };

        $order->update($updateData);

        // Disparar notificação para Slack
        Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new OrderStatusUpdated($order, $currentStatus, $newStatus, $reason));
    }

    /**
     * Obtém as transições permitidas com labels traduzidos
     */
    public function getPossibleTransitionsLabeled(string $status): array
    {
        $possible = $this->getPossibleTransitions($status);
        
        $labels = [
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
