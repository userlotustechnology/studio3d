@extends('layouts.main')

@section('title', 'Pedidos - Admin')

@section('content')
<div style="padding: 24px;">
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Todos os Pedidos</h1>
        <p style="color: #6b7280;">Gerenciar e acompanhar todos os pedidos da loja</p>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Total de Pedidos</p>
            <p style="color: #1f2937; font-weight: 700; font-size: 28px; margin: 0;">{{ $totalOrders }}</p>
        </div>
        <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Rascunhos</p>
            <p style="color: #6b7280; font-weight: 700; font-size: 28px; margin: 0;">{{ $draftCount }}</p>
        </div>
        <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Pendentes</p>
            <p style="color: #f59e0b; font-weight: 700; font-size: 28px; margin: 0;">{{ $pendingCount }}</p>
        </div>
        <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin: 0 0 8px 0;">Entregues</p>
            <p style="color: #10b981; font-weight: 700; font-size: 28px; margin: 0;">{{ $deliveredCount }}</p>
        </div>
    </div>

    <!-- Filtros -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Pedido</label>
                <input type="text" name="search" placeholder="Número do pedido ou cliente" value="{{ request('search') }}"
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos os status</option>
                    <option value="draft" @if(request('status') === 'draft') selected @endif>Rascunho</option>
                    <option value="pending" @if(request('status') === 'pending') selected @endif>Pendente</option>
                    <option value="processing" @if(request('status') === 'processing') selected @endif>Processando</option>
                    <option value="shipped" @if(request('status') === 'shipped') selected @endif>Enviado</option>
                    <option value="delivered" @if(request('status') === 'delivered') selected @endif>Entregue</option>
                    <option value="cancelled" @if(request('status') === 'cancelled') selected @endif>Cancelado</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Período</label>
                <select name="period" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos</option>
                    <option value="today" @if(request('period') === 'today') selected @endif>Hoje</option>
                    <option value="week" @if(request('period') === 'week') selected @endif>Esta semana</option>
                    <option value="month" @if(request('period') === 'month') selected @endif>Este mês</option>
                </select>
            </div>
            <button type="submit" style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Table -->
    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937;">Pedido</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937;">Cliente</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1f2937;">Email</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937;">Total</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937;">Status</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937;">Data</th>
                        <th style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $order->order_number }}</div>
                            <div style="color: #6b7280; font-size: 12px;">#{{ $order->id }}</div>
                        </td>
                        <td style="padding: 16px; color: #1f2937; font-weight: 500;">{{ $order->customer?->name ?? 'N/A' }}</td>
                        <td style="padding: 16px; color: #6b7280; font-size: 13px;">{{ $order->customer?->email ?? 'N/A' }}</td>
                        <td style="padding: 16px; text-align: center; color: #1f2937; font-weight: 600;">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                        <td style="padding: 16px; text-align: center;">
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
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;
                                background-color: {{ $colors['bg'] }};
                                color: {{ $colors['text'] }};">
                                {{ translateOrderStatus($order->status) }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 13px;">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <a href="{{ route('admin.orders.show', $order->uuid) }}" style="background: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                Visualizar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 48px; text-align: center;">
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
@endsection
