@extends('layouts.main')

@section('title', 'Vendas Detalhadas - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Vendas Detalhadas</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Últimos 30 dias - Análise dia a dia</p>
            </div>
            <a href="{{ route('admin.finance.index') }}" style="padding: 10px 20px; background-color: #6b7280; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                <i class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">arrow_back</i> Voltar
            </a>
        </div>

        <!-- Daily Sales Chart -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">Vendas Diárias (Últimos 30 dias)</h3>
            <div id="salesChart" style="height: 400px;"></div>
        </div>

        <!-- Sales Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">ID do Pedido</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Cliente</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Email</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Itens</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Status</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Subtotal</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Frete</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Desconto</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Total</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Data</th>
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
                            <p style="color: #1f2937; font-weight: 500; margin: 0; font-size: 14px;">{{ $order->customer?->name ?? 'N/A' }}</p>
                        </td>
                        <td style="padding: 16px;">
                            <p style="color: #6b7280; margin: 0; font-size: 13px;">{{ $order->customer?->email ?? 'N/A' }}</p>
                        </td>
                        <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 14px;">
                            {{ $order->items->count() }}
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @php
                            $statusColors = [
                                'pending' => '#fbbf24',
                                'processing' => '#60a5fa',
                                'shipped' => '#a78bfa',
                                'delivered' => '#34d399',
                                'cancelled' => '#f87171',
                            ];
                            $statusColor = $statusColors[$order->status] ?? '#6b7280';
                            @endphp
                            <span style="background-color: {{ $statusColor }}20; color: {{ $statusColor }}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ translateOrderStatus($order->status) }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: right; color: #6b7280; font-size: 14px;">
                            R$ {{ number_format($order->subtotal, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px; text-align: right; color: #6b7280; font-size: 14px;">
                            R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px; text-align: right; color: #6b7280; font-size: 14px;">
                            R$ {{ number_format($order->discount, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px; text-align: right; color: #1f2937; font-weight: 600; font-size: 14px;">
                            R$ {{ number_format($order->total, 2, ',', '.') }}
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 13px;">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <a href="{{ route('admin.orders.show', $order->uuid) }}" style="background-color: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" style="padding: 40px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0;">Nenhuma venda encontrada</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesChartData = {
        labels: [
            @foreach($salesByDay as $sale)
            "{{ \Carbon\Carbon::parse($sale->date)->format('d/m') }}",
            @endforeach
        ],
        orders: [
            @foreach($salesByDay as $sale)
            {{ $sale->count }},
            @endforeach
        ],
        revenue: [
            @foreach($salesByDay as $sale)
            {{ $sale->revenue }},
            @endforeach
        ]
    };

    const salesChart = new ApexCharts(document.getElementById('salesChart'), {
        chart: {
            type: 'line',
            toolbar: { show: true },
            zoom: { enabled: true }
        },
        series: [
            {
                name: 'Pedidos',
                data: salesChartData.orders,
                yaxis: {
                    seriesName: 'Pedidos',
                }
            },
            {
                name: 'Receita (R$)',
                data: salesChartData.revenue,
                yaxis: {
                    seriesName: 'Receita (R$)',
                    opposite: true
                }
            }
        ],
        xaxis: {
            categories: salesChartData.labels,
        },
        stroke: {
            curve: 'smooth',
            width: [2, 2]
        },
        colors: ['#3b82f6', '#10b981'],
        dataLabels: { enabled: false },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(val, { series, seriesIndex, dataPointIndex, w }) {
                    if (seriesIndex === 0) {
                        return val + ' pedidos';
                    }
                    return 'R$ ' + val.toFixed(2).replace('.', ',');
                }
            }
        },
        yaxis: [
            {
                axisTicks: { show: true },
                axisBorder: {
                    show: true,
                    color: '#3b82f6'
                },
                labels: {
                    style: {
                        colors: '#3b82f6',
                    }
                },
                title: {
                    text: 'Quantidade de Pedidos',
                    style: {
                        color: '#3b82f6',
                    }
                }
            },
            {
                opposite: true,
                axisTicks: { show: true },
                axisBorder: {
                    show: true,
                    color: '#10b981'
                },
                labels: {
                    style: {
                        colors: '#10b981',
                    }
                },
                title: {
                    text: 'Receita (R$)',
                    style: {
                        color: '#10b981',
                    }
                }
            }
        ]
    });
    salesChart.render();
});
</script>
@endsection
