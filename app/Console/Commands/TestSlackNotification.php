<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SlackNotificationService;

class TestSlackNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slack:test {orderId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Slack notification configuration and send a test notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $webhookUrl = config('services.slack.webhook_url') ?? env('SLACK_WEBHOOK_URL');
        
        $this->info('=== Slack Notification Test ===');
        $this->info('');
        
        // Check webhook URL
        if (!$webhookUrl) {
            $this->error('❌ SLACK_WEBHOOK_URL is not configured!');
            $this->info('Please set SLACK_WEBHOOK_URL in your .env file');
            return 1;
        }
        
        $this->info('✓ Webhook URL configured');
        $this->info('URL (masked): ' . substr($webhookUrl, 0, 50) . '...');
        $this->info('');
        
        // Send test notification
        $slackService = new SlackNotificationService();
        
        $orderId = $this->argument('orderId') ?? 'TEST-' . time();
        
        $this->info('Sending test notification for order: ' . $orderId);
        
        $result = $slackService->send(
            '🧪 Notificação de Teste',
            'Esta é uma notificação de teste para verificar a configuração do Slack.',
            [
                [
                    'title' => 'Tipo',
                    'value' => 'Teste de Configuração',
                    'short' => true,
                ],
                [
                    'title' => 'Timestamp',
                    'value' => date('Y-m-d H:i:s'),
                    'short' => true,
                ],
            ],
            '#0f79f3'
        );
        
        if ($result) {
            $this->info('✓ Notificação enviada com sucesso!');
            return 0;
        } else {
            $this->error('❌ Erro ao enviar notificação. Verifique os logs.');
            return 1;
        }
    }
}
