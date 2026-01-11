@extends('layouts.main')

@section('title', 'Relatório Financeiro - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Relatório Financeiro</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Análise completa de vendas e receita</p>
            </div>
            <a href="{{ route('admin.finance.sales') }}" style="padding: 10px 20px; background-color: #3b82f6; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                <i class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">trending_up</i> Ver Vendas Detalhadas
            </a>
        </div>

        <!-- KPI Cards - Main Metrics -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Total Revenue -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Receita Total</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #10b981; margin: 8px 0 0 0;">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #d1fae5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #10b981; font-size: 28px;">monetization_on</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">{{ $totalOrders }} pedidos processados</p>
            </div>

            <!-- Subtotal -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Subtotal Produtos</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #3b82f6; margin: 8px 0 0 0;">R$ {{ number_format($totalSubtotal, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #3b82f6; font-size: 28px;">shopping_bag</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Valor antes de descontos e frete</p>
            </div>

            <!-- Average Ticket -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Ticket Médio</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #8b5cf6; margin: 8px 0 0 0;">R$ {{ number_format($averageTicket, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #ede9fe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #8b5cf6; font-size: 28px;">poll</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Valor médio por pedido</p>
            </div>

            <!-- Total Shipping -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Frete Total</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #f59e0b; margin: 8px 0 0 0;">R$ {{ number_format($totalShipping, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #f59e0b; font-size: 28px;">local_shipping</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Custos com entrega</p>
            </div>

            <!-- Total Discounts -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Descontos Concedidos</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #ef4444; margin: 8px 0 0 0;">R$ {{ number_format($totalDiscount, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #ef4444; font-size: 28px;">local_offer</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Descontos em cupons</p>
            </div>

            <!-- Total Orders -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Total de Pedidos</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #06b6d4; margin: 8px 0 0 0;">{{ $totalOrders }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #cffafe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #06b6d4; font-size: 28px;">receipt_long</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Pedidos completados</p>
            </div>
        </div>

        <!-- Periods Quick Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px;">
            @foreach($periodMetrics as $key => $metric)
            <div style="background: white; border-radius: 8px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin: 0 0 8px 0;">{{ $metric['label'] }}</p>
                <div style="display: flex; justify-content: space-between; align-items: baseline;">
                    <div>
                        <p style="color: #1f2937; font-size: 18px; font-weight: 700; margin: 0;">{{ $metric['orders'] }}</p>
                        <p style="color: #6b7280; font-size: 11px; margin: 4px 0 0 0;">pedidos</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="color: #10b981; font-size: 14px; font-weight: 600; margin: 0;">R$ {{ number_format($metric['revenue'], 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Charts Row -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Revenue Chart -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">Receita por Mês (12 meses)</h3>
                <div id="revenueChart" style="height: 300px;"></div>
            </div>

            <!-- Status Distribution -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">Distribuição por Status</h3>
                <div id="statusChart" style="height: 300px;"></div>
            </div>
        </div>

        <!-- Status Cards -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">Pedidos por Status</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <div style="padding: 16px; background-color: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <p style="color: #7c2d12; font-size: 11px; font-weight: 600; margin: 0;">PENDENTES</p>
                    <p style="color: #f59e0b; font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">{{ $pendingOrders }}</p>
                </div>
                <div style="padding: 16px; background-color: #dbeafe; border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <p style="color: #1e40af; font-size: 11px; font-weight: 600; margin: 0;">PROCESSANDO</p>
                    <p style="color: #3b82f6; font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">{{ $processingOrders }}</p>
                </div>
                <div style="padding: 16px; background-color: #ede9fe; border-radius: 8px; border-left: 4px solid #8b5cf6;">
                    <p style="color: #5b21b6; font-size: 11px; font-weight: 600; margin: 0;">ENVIADOS</p>
                    <p style="color: #8b5cf6; font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">{{ $shippedOrders }}</p>
                </div>
                <div style="padding: 16px; background-color: #d1fae5; border-radius: 8px; border-left: 4px solid #10b981;">
                    <p style="color: #065f46; font-size: 11px; font-weight: 600; margin: 0;">ENTREGUES</p>
                    <p style="color: #10b981; font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">{{ $deliveredOrders }}</p>
                </div>
                <div style="padding: 16px; background-color: #fee2e2; border-radius: 8px; border-left: 4px solid #ef4444;">
                    <p style="color: #7f1d1d; font-size: 11px; font-weight: 600; margin: 0;">CANCELADOS</p>
                    <p style="color: #ef4444; font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">{{ $cancelledOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">Top 10 Produtos Mais Vendidos</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px; text-align: left; color: #6b7280; font-weight: 600; font-size: 12px;">Produto</th>
                        <th style="padding: 12px; text-align: center; color: #6b7280; font-weight: 600; font-size: 12px;">Quantidade Vendida</th>
                        <th style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">Preço Unitário</th>
                        <th style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">Receita Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $product)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; color: #1f2937; font-weight: 500; font-size: 14px;">{{ $product->name }}</td>
                        <td style="padding: 12px; text-align: center; color: #1f2937; font-size: 14px;">
                            <span style="background-color: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                {{ $product->total_quantity }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right; color: #6b7280; font-size: 14px;">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td style="padding: 12px; text-align: right; color: #10b981; font-weight: 600; font-size: 14px;">R$ {{ number_format($product->total_revenue, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 40px; text-align: center; color: #6b7280;">
                            <p style="margin: 0;">Nenhum produto vendido ainda</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueChartData = {
        labels: [
            @foreach($revenueByMonth as $data)
            "{{ $data['month'] }}",
            @endforeach
        ],
        series: [{
            name: 'Receita',
            data: [
                @foreach($revenueByMonth as $data)
                {{ $data['revenue'] }},
                @endforeach
            ]
        }]
    };

    const revenueChart = new ApexCharts(document.getElementById('revenueChart'), {
        chart: {
            type: 'area',
            sparkline: { enabled: false },
            toolbar: { show: true }
        },
        series: revenueChartData.series,
        xaxis: {
            categories: revenueChartData.labels,
        },
        colors: ['#10b981'],
        stroke: { curve: 'smooth' },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            }
        },
        dataLabels: { enabled: false }
    });
    revenueChart.render();

    // Status Chart
    const statusChartData = {
        labels: [
            @foreach($revenueByStatus as $status)
            "{{ translateOrderStatus($status->status) }}",
            @endforeach
        ],
        data: [
            @foreach($revenueByStatus as $status)
            {{ $status->count }},
            @endforeach
        ]
    };

    const statusChart = new ApexCharts(document.getElementById('statusChart'), {
        chart: {
            type: 'donut',
        },
        series: statusChartData.data,
        labels: statusChartData.labels,
        colors: ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + '%'
            }
        }
    });
    statusChart.render();
});
</script>
@endsection
