<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderStatusTransitionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdateMail;

class UpdateOrderTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update-tracking 
                            {order_id : ID do pedido}
                            {tracking_code : Código de rastreio}
                            {shipping_company_id : ID da empresa de entrega}
                            {--send-email : Enviar e-mail de notificação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o código de rastreio de um pedido e marca como enviado';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $orderId = $this->argument('order_id');
        $trackingCode = $this->argument('tracking_code');
        $shippingCompanyId = $this->argument('shipping_company_id');
        $sendEmail = $this->option('send-email');

        // Buscar o pedido
        $order = Order::find($orderId);
        if (!$order) {
            $this->error("Pedido #{$orderId} não encontrado.");
            return 1;
        }

        $previousStatus = $order->status;
        $transitionService = app(OrderStatusTransitionService::class);

        // Validar transição para 'shipped'
        if (!$transitionService->canTransition($previousStatus, 'shipped')) {
            $validTransitions = implode(', ', $transitionService->getPossibleTransitions($previousStatus));
            $this->error("✗ Transição inválida de '{$previousStatus}' para 'shipped'");
            $this->line("Transições válidas: {$validTransitions}");
            return 1;
        }

        try {
            // Atualizar rastreio
            $order->update([
                'tracking_code' => $trackingCode,
                'shipping_company_id' => $shippingCompanyId,
            ]);

            // Realizar transição de status
            $transitionService->transitionStatus(
                $order,
                'shipped',
                "Código de rastreio: {$trackingCode}",
                'command'
            );

            $order->refresh();

            $this->info("✓ Rastreio atualizado com sucesso!");
            $this->line("  Pedido: {$order->order_number}");
            $this->line("  Status: {$previousStatus} → shipped");
            $this->line("  Código: {$trackingCode}");
            $this->line("  Transportadora: {$order->shippingCompany->name}");

            // Enviar e-mail de notificação (assíncrono com delay de 1 minuto)
            if ($sendEmail) {
                try {
                    Mail::queue(new OrderStatusUpdateMail($order, $previousStatus, 'shipped'));
                    $this->info("✓ E-mail de notificação enfileirado e será enviado em 1 minuto!");
                } catch (\Exception $e) {
                    $this->error("✗ Erro ao enfileirar e-mail: " . $e->getMessage());
                    return 1;
                }
            }

            return 0;
        } catch (\InvalidArgumentException $e) {
            $this->error("✗ Erro: " . $e->getMessage());
            return 1;
        }
    }
}
