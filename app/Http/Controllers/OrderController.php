<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdateMail;
use App\Mail\OrderCancelledMail;
use App\Models\Order;
use App\Models\StockMovement;
use App\Services\OrderStatusTransitionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct(private OrderStatusTransitionService $transitionService) {}

    public function index(): View
    {
        // Filtrar apenas pedidos que não são draft (finalizados pelo cliente)
        $orders = Order::where('is_draft', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function pending(): View
    {
        $orders = Order::where('is_draft', false)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('orders.pending', compact('orders'));
    }

    public function completed(): View
    {
        $orders = Order::where('is_draft', false)
            ->where('status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('orders.completed', compact('orders'));
    }

    public function show(string $uuid): View
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->load('items', 'customer', 'shippingAddress', 'billingAddress');
        
        // Obter transições permitidas para o status atual
        $allowedTransitions = $this->transitionService->getPossibleTransitionsLabeled($order->status);
        $allowedStatuses = array_column($allowedTransitions, 'status');
        
        return view('orders.show', compact('order', 'allowedStatuses', 'allowedTransitions'));
    }

    public function updateStatus(string $uuid, string $status): \Illuminate\Http\RedirectResponse
    {
        try {
            $order = Order::where('uuid', $uuid)->firstOrFail();
            $previousStatus = $order->status;
            $reason = "Atualização manual de status";

            // Realizar transição com validação
            $this->transitionService->transitionStatus(
                $order,
                $status,
                $reason,
                'admin'
            );

            // Recarregar para pegar o status atualizado
            $order->refresh();

            // Se foi cancelado, devolver produtos ao estoque
            if ($status === 'cancelled') {
                foreach ($order->items as $item) {
                    StockMovement::recordMovement(
                        $item->product_id,
                        'in',
                        $item->quantity,
                        "Devolução por cancelamento - Pedido #{$order->order_number}",
                        $order->id,
                        auth()->user()->name ?? 'Sistema'
                    );
                }
                Mail::queue(new OrderCancelledMail($order, $reason));
            } else {
                Mail::queue(new OrderStatusUpdateMail($order, $previousStatus, $status));
            }

            return back()->with('success', 'Status do pedido atualizado com sucesso!');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateCarrierCost(string $uuid, Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        
        // Verificar se o pedido ainda pode ter o custo alterado
        $allowedStatuses = ['pending', 'processing'];
        
        if (!in_array($order->status, $allowedStatuses)) {
            return back()->with('error', 'O custo da transportadora só pode ser alterado até o status "Processando".');
        }

        $validated = $request->validate([
            'carrier_shipping_cost' => 'nullable|numeric|min:0|max:9999.99'
        ]);

        try {
            $order->update([
                'carrier_shipping_cost' => $validated['carrier_shipping_cost']
            ]);

            return back()->with('success', 'Custo da transportadora atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar custo: ' . $e->getMessage());
        }
    }

    public function refund(string $uuid, Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $order = Order::where('uuid', $uuid)->firstOrFail();
            
            // Verificar se o pedido pode ser estornado (apenas após enviado ou entregue)
            if (!in_array($order->status, ['shipped', 'delivered'])) {
                return back()->with('error', 'Pedidos só podem ser estornados após o status "Enviado".');
            }

            $validated = $request->validate([
                'refund_reason' => 'required|string|max:500'
            ]);

            $previousStatus = $order->status;
            $reason = $validated['refund_reason'];

            // Realizar transição para refunded
            $this->transitionService->transitionStatus(
                $order,
                'refunded',
                "Estorno solicitado: {$reason}",
                auth()->user()->name ?? 'admin'
            );

            // Devolver produtos ao estoque
            foreach ($order->items as $item) {
                if ($item->product && $item->product->type === 'physical') {
                    StockMovement::recordMovement(
                        $item->product_id,
                        'in',
                        $item->quantity,
                        "Devolução por estorno - Pedido #{$order->order_number}",
                        $order->id,
                        auth()->user()->name ?? 'Sistema'
                    );
                }
            }

            // Registrar no livro caixa (débito - saída de dinheiro para devolver ao cliente)
            \App\Models\CashBook::create([
                'transaction_date' => now(),
                'type' => 'debit',
                'category' => 'refund',
                'description' => "Estorno do pedido #{$order->order_number} - {$reason}",
                'amount' => $order->total,
                'payment_method_id' => \App\Models\PaymentMethod::where('code', $order->payment_method)->first()?->id,
                'order_id' => $order->id,
                'fee_amount' => 0,
                'net_amount' => -$order->total,
            ]);

            // Enviar email de notificação
            Mail::queue(new OrderCancelledMail($order, "Pedido estornado: {$reason}"));

            return back()->with('success', 'Pedido estornado com sucesso! O estoque foi atualizado e o valor foi registrado no livro caixa.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao processar estorno: ' . $e->getMessage());
        }
    }
}
