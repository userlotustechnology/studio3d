@extends('layouts.main')

@section('title', 'Relatório de Cashback Concedido')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px; min-height: 100vh;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0;">
                <i class="fas fa-gift"></i> Relatório de Cashback Concedido
            </h1>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">
                Visualize todo o cashback concedido aos clientes por período
            </p>
        </div>

        <!-- Filtros -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 30px;">
            <form method="GET" action="{{ route('admin.reports.cashback') }}">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">Data Inicial</label>
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                            style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">Data Final</label>
                        <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                            style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">Cliente</label>
                        <select name="customer_id" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                            <option value="">Todos os clientes</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 10px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.reports.cashback') }}" style="background-color: #6b7280; color: white; padding: 10px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-redo"></i> Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Cards de Totalizadores -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Total Concedido</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($totalCashbackGranted, 2, ',', '.') }}</h3>
                <i class="fas fa-gift" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Cashback Ativo</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($totalCashbackActive, 2, ',', '.') }}</h3>
                <i class="fas fa-check-circle" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Cashback Estornado</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($totalCashbackRefunded, 2, ',', '.') }}</h3>
                <i class="fas fa-times-circle" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Média por Pedido</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($averageCashbackPerOrder, 2, ',', '.') }}</h3>
                <i class="fas fa-chart-line" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>
        </div>

        <!-- Resumo por Cliente -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px;">
            <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                    <i class="fas fa-users"></i> Resumo por Cliente
                </h5>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Cliente</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">Pedidos</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Total Cashback</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Cashback Ativo</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Cashback Estornado</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Saldo Atual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customersSummary as $summary)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 16px;">
                                    <a href="{{ route('admin.crm.customers.show', $summary['customer']) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                        {{ $summary['customer']->name }}
                                    </a>
                                    <div style="color: #6b7280; font-size: 12px; margin-top: 4px;">{{ $summary['customer']->email }}</div>
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        {{ $summary['total_orders'] }}
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: right; color: #6b7280;">R$ {{ number_format($summary['total_cashback'], 2, ',', '.') }}</td>
                                <td style="padding: 16px; text-align: right;">
                                    <strong style="color: #10b981;">R$ {{ number_format($summary['active_cashback'], 2, ',', '.') }}</strong>
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <strong style="color: #ef4444;">R$ {{ number_format($summary['refunded_cashback'], 2, ',', '.') }}</strong>
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <strong style="color: #8b5cf6; font-size: 16px;">R$ {{ number_format($summary['customer']->cashback_balance, 2, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px; text-align: center; color: #6b7280;">
                                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Nenhum cashback concedido no período</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detalhamento de Pedidos -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                    <i class="fas fa-list"></i> Detalhamento de Pedidos ({{ $totalOrders }})
                </h5>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Pedido</th>
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Cliente</th>
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Data</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Subtotal</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">% Cashback</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Valor Cashback</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 16px;">
                                    <a href="{{ route('orders.show', $order->uuid) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td style="padding: 16px; color: #6b7280;">{{ $order->customer->name }}</td>
                                <td style="padding: 16px; color: #6b7280;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td style="padding: 16px; text-align: right; color: #6b7280;">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                <td style="padding: 16px; text-align: center;">
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        {{ number_format($order->cashback_percentage, 2, ',', '.') }}%
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <strong style="color: #10b981; font-size: 16px;">R$ {{ number_format($order->cashback_amount, 2, ',', '.') }}</strong>
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    @if(in_array($order->status, ['cancelled', 'refunded']))
                                        <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            <i class="fas fa-times"></i> Estornado
                                        </span>
                                    @else
                                        <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            <i class="fas fa-check"></i> Ativo
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280;">
                                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Nenhum pedido encontrado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
