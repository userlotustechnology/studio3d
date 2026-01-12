@extends('layouts.main')

@section('title', 'Pedidos - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Todos os Pedidos</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerenciar e acompanhar todos os pedidos da loja</p>
        </div>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Total de Pedidos</p>
                <p style="color: #1f2937; font-weight: 700; font-size: 28px; margin: 0;">{{ $orders->total() }}</p>
            </div>
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Pendentes</p>
                <p style="color: #f59e0b; font-weight: 700; font-size: 28px; margin: 0;">{{ $orders->where('status', 'pending')->count() }}</p>
            </div>
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Entregues</p>
                <p style="color: #10b981; font-weight: 700; font-size: 28px; margin: 0;">{{ $orders->where('status', 'delivered')->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">ID / Número</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">Cliente</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">Email</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">Data</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">Itens</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">Total</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937; font-size: 13px;">Status</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937; font-size: 13px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 16px; color: #1f2937;">
                                <div style="font-weight: 600;">#{{ $order->id }}</div>
                                <div style="color: #6b7280; font-size: 12px;">{{ $order->order_number }}</div>
                            </td>
                            <td style="padding: 16px; color: #1f2937; font-weight: 500;">{{ $order->customer?->name ?? 'N/A' }}</td>
                            <td style="padding: 16px; color: #6b7280; font-size: 13px;">{{ $order->customer?->email ?? 'N/A' }}</td>
                            <td style="padding: 16px; color: #6b7280; font-size: 13px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td style="padding: 16px; color: #1f2937; font-weight: 600;">{{ $order->items->count() }}</td>
                            <td style="padding: 16px; color: #1f2937; font-weight: 600;">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                            <td style="padding: 16px;">
                                @php
                                    $statusColors = [
                                        'draft' => ['bg' => '#f3f4f6', 'text' => '#6b7280'],
                                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                        'shipped' => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                                        'delivered' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $colors = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280'];
                                @endphp
                                <span style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;
                                    background-color: {{ $colors['bg'] }};
                                    color: {{ $colors['text'] }};">
                                    {{ translateOrderStatus($order->status) }}
                                </span>
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <a href="{{ route('admin.orders.show', $order->id) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; font-size: 13px;">
                                    Visualizar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="padding: 40px; text-align: center;">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px; display: block;"></i>
                                <p style="color: #6b7280; margin: 0;">Nenhum pedido encontrado</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div style="padding: 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
