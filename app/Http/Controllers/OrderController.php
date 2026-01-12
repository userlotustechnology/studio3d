<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdateMail;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
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
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Status inválido');
        }

        // Capturar status anterior
        $previousStatus = $order->status;

        // Atualizar status
        $order->update(['status' => $status]);

        // Enviar email de atualização de status (assíncrono com delay de 1 minuto)
        Mail::queue(new OrderStatusUpdateMail($order, $previousStatus, $status));

        return back()->with('success', 'Status do pedido atualizado com sucesso!');
    }
}
