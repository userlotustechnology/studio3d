<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Services\SlackNotificationService;

class SendOrderCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private SlackNotificationService $slackService,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(OrderConfirmed $event): void
    {
        // Prevenir duplicação - salvar um registro de que já processamos este pedido
        $lockKey = 'slack-notification-order-' . $event->order->id;
        
        // Se a chave já existe em cache, não processa novamente
        if (\Illuminate\Support\Facades\Cache::has($lockKey)) {
            \Illuminate\Support\Facades\Log::info('Slack notification for order ' . $event->order->id . ' already sent (duplicate prevention)');
            return;
        }
        
        \Illuminate\Support\Facades\Log::info('SendOrderCreatedNotification dispatcher called for order: ' . $event->order->id);
        
        // Marcar como processado por 1 hora
        \Illuminate\Support\Facades\Cache::put($lockKey, true, now()->addHour());
        
        $this->slackService->notifyOrderCreated($event->order);
    }
}
