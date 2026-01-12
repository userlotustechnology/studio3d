@extends('layouts.main')

@section('title', 'PDV - Ponto de Venda')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">PDV - Ponto de Venda</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Sistema de vendas interno</p>
            </div>
            <a href="{{ route('admin.pos.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle"></i>
                Nova Venda
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Vendas Recentes -->
            <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clock" style="color: #667eea;"></i>
                    Vendas Recentes
                </h2>

                @if($recentOrders->count() > 0)
                    <div style="overflow: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Pedido</th>
                                    <th style="padding: 12px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Cliente</th>
                                    <th style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Total</th>
                                    <th style="padding: 12px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Status</th>
                                    <th style="padding: 12px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Data</th>
                                    <th style="padding: 12px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                                    <td style="padding: 16px; color: #1f2937; font-weight: 600;">{{ $order->order_number }}</td>
                                    <td style="padding: 16px; color: #6b7280;">{{ $order->customer?->name ?? 'N/A' }}</td>
                                    <td style="padding: 16px; text-align: right; color: #1f2937; font-weight: 600;">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
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
                                        <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                            {{ translateOrderStatus($order->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 14px;">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td style="padding: 16px; text-align: center;">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" style="background-color: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <h3 style="margin: 0 0 8px 0; font-weight: 600;">Nenhuma venda realizada</h3>
                        <p style="margin: 0; font-size: 14px;">Comece criando sua primeira venda através do PDV</p>
                    </div>
                @endif
            </div>

            <!-- Estatísticas Rápidas -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Vendas de Hoje -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-day" style="color: white; font-size: 18px;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 14px; color: #6b7280; font-weight: 500;">Vendas de Hoje</h3>
                            <p style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">
                                {{ \App\Models\Order::whereDate('created_at', today())->where('is_draft', false)->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Faturamento do Mês -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line" style="color: white; font-size: 18px;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 14px; color: #6b7280; font-weight: 500;">Faturamento do Mês</h3>
                            <p style="margin: 0; font-size: 18px; font-weight: 700; color: #1f2937;">
                                R$ {{ number_format(\App\Models\Order::whereMonth('created_at', now()->month)->where('is_draft', false)->sum('total'), 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Produtos em Estoque Baixo -->
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 18px;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 14px; color: #6b7280; font-weight: 500;">Estoque Baixo</h3>
                            <p style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">
                                {{ \App\Models\Product::where('stock_quantity', '<=', 5)->where('is_active', true)->count() }}
                            </p>
                        </div>
                    </div>
                    @if(\App\Models\Product::where('stock_quantity', '<=', 5)->where('is_active', true)->count() > 0)
                        <div style="margin-top: 12px;">
                            <a href="{{ route('admin.products.index') }}" style="color: #f59e0b; text-decoration: none; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-arrow-right"></i> Ver Produtos
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Ação Rápida -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 20px; text-align: center; color: white;">
                    <i class="fas fa-cash-register" style="font-size: 32px; margin-bottom: 12px; opacity: 0.9;"></i>
                    <h3 style="margin: 0 0 8px 0; font-weight: 700;">Pronto para vender?</h3>
                    <p style="margin: 0 0 16px 0; font-size: 14px; opacity: 0.9;">Crie uma nova venda rapidamente</p>
                    <a href="{{ route('admin.pos.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; backdrop-filter: blur(10px);">
                        Iniciar Venda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection