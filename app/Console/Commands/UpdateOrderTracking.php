<?php

namespace App\Console\Commands;

use App\Models\Order;
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
    protected $description = 'Atualiza o código de rastreio de um pedido e opcionalmente envia notificação por e-mail';

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

        // Validar status
        if ($order->status !== 'processing') {
            $this->warn("Atenção: O pedido está com status '{$order->status}'. Normalmente pedidos devem estar em 'processing' antes de marcar como enviado.");
        }

        $previousStatus = $order->status;

        // Atualizar rastreio
        $order->update([
            'tracking_code' => $trackingCode,
            'shipping_company_id' => $shippingCompanyId,
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        $this->info("✓ Rastreio atualizado com sucesso!");
        $this->line("  Pedido: {$order->order_number}");
        $this->line("  Código: {$trackingCode}");
        $this->line("  Transportadora: {$order->shippingCompany->name}");

        // Enviar e-mail de notificação
        if ($sendEmail) {
            try {
                Mail::send(new OrderStatusUpdateMail($order, $previousStatus, 'shipped'));
                $this->info("✓ E-mail de notificação enviado!");
            } catch (\Exception $e) {
                $this->error("✗ Erro ao enviar e-mail: " . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}
