<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdateMail;
use App\Mail\OrderCancelledMail;
use App\Models\Order;
use App\Services\OrderStatusTransitionService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct(private OrderStatusTransitionService $transitionService) {}

    public function index(): View
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function pending(): View
    {
        $orders = Order::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('orders.pending', compact('orders'));
    }

    public function completed(): View
    {
        $orders = Order::where('status', 'delivered')
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
}
