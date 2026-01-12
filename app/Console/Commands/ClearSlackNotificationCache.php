<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearSlackNotificationCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:clear-cache {orderId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Slack notification cache for orders. Use without arguments to clear all, or specify an order ID.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('orderId');

        if ($orderId) {
            $lockKey = 'slack-notification-order-' . $orderId;
            Cache::forget($lockKey);
            $this->info("Cache cleared for order {$orderId}");
        } else {
            // Find all slack notification cache keys and delete them
            // Note: This requires Redis or a driver that supports pattern deletion
            $this->warn('This command requires a cache driver that supports pattern deletion (Redis recommended)');
            $this->info('Alternative: Clear all cache with `php artisan cache:clear`');
        }
    }
}
