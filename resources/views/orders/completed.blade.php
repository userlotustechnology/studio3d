@extends('layouts.main')

@section('title', 'Pedidos Entregues - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Pedidos Entregues</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Histórico de pedidos entregues</p>
        </div>

        <!-- Pedidos Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Pedido</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Cliente</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Data</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Itens</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Total</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px;">
                            <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $order->order_number }}</p>
                        </td>
                        <td style="padding: 16px;">
                            <p style="color: #1f2937; font-weight: 500; margin: 0; font-size: 14px;">{{ $order->customer_name }}</p>
                            <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 12px;">{{ $order->customer_email }}</p>
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                            {{ $order->items->count() }} item(ns)
                        </td>
                        <td style="padding: 16px; color: #1f2937; font-weight: 600; font-size: 14px;">
                            R$ {{ number_format($order->total, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <a href="{{ route('admin.orders.show', $order->id) }}" style="background-color: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0;">Nenhum pedido entregue encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
