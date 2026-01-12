<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdateMail;
use App\Mail\OrderCancelledMail;
use App\Models\Order;
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

    public function show(Order $order): View
    {
        $order->load('items', 'customer', 'shippingAddress', 'billingAddress');
        
        // Obter transições permitidas para o status atual
        $allowedTransitions = $this->transitionService->getPossibleTransitionsLabeled($order->status);
        $allowedStatuses = array_column($allowedTransitions, 'status');
        
        return view('orders.show', compact('order', 'allowedStatuses', 'allowedTransitions'));
    }

    public function updateStatus(Order $order, string $status): \Illuminate\Http\RedirectResponse
    {
        try {
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

            // Disparar email específico para cancelamento, ou email genérico para outros status
            if ($status === 'cancelled') {
                Mail::queue(new OrderCancelledMail($order, $reason));
            } else {
                Mail::queue(new OrderStatusUpdateMail($order, $previousStatus, $status));
            }

            return back()->with('success', 'Status do pedido atualizado com sucesso!');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateCarrierCost(Order $order, Request $request): \Illuminate\Http\RedirectResponse
    {
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
}
