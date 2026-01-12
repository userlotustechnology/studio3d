<?php

namespace App\Listeners;

use App\Events\OrderCreated;
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
    public function handle(OrderCreated $event): void
    {
        $this->slackService->notifyOrderCreated($event->order);
    }
}
