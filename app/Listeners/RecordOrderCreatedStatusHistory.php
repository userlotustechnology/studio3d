<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Models\OrderStatusHistory;

class RecordOrderCreatedStatusHistory
{
    /**
     * Handle the event.
     */
    public function handle(OrderConfirmed $event): void
    {
        // Registrar a transição inicial do status quando o pedido é criado
        // De: null/draft → para: pending
        OrderStatusHistory::create([
            'order_id' => $event->order->id,
            'from_status' => 'pending', // Registrar como initial state
            'to_status' => 'pending',
            'reason' => 'Pedido criado',
            'changed_by' => 'system',
        ]);
    }
}
