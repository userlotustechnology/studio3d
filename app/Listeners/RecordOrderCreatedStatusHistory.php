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
        // O histórico da transição draft → pending já é registrado no CartController
        // Este listener pode ser usado para outras ações futuras se necessário
        \Illuminate\Support\Facades\Log::info('Order confirmed event received for order: ' . $event->order->id);
    }
}
