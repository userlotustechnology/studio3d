<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdateMail;
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
        return view('orders.show', compact('order'));
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

            // Enviar email de atualização de status (assíncrono com delay de 1 minuto)
            Mail::queue(new OrderStatusUpdateMail($order, $previousStatus, $status));

            return back()->with('success', 'Status do pedido atualizado com sucesso!');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
