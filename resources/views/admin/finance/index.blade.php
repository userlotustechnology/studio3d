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
                <p style="color: #6b7280; font-size: 12px; margin: 0;">{{ $totalOrders }} pedidos | Ticket: R$ {{ number_format($averageTicket, 2, ',', '.') }}</p>
            </div>

            <!-- Gross Profit -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Lucro Bruto</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #3b82f6; margin: 8px 0 0 0;">R$ {{ number_format($totalProductProfit, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #3b82f6; font-size: 28px;">trending_up</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Margem: {{ number_format($averageProfitMargin, 1, ',', '.') }}% | Produtos vendidos</p>
            </div>

            <!-- Shipping Profit -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Lucro com Frete</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #f59e0b; margin: 8px 0 0 0;">R$ {{ number_format($shippingProfit, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #f59e0b; font-size: 28px;">local_shipping</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Receita: R$ {{ number_format($totalShippingRevenue, 2, ',', '.') }} | Custo: R$ {{ number_format($totalShippingCost, 2, ',', '.') }}</p>
            </div>

            <!-- Cashback & Discounts -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Descontos & Cashback</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #ef4444; margin: 8px 0 0 0;">R$ {{ number_format($totalDiscount + $totalCashbackActive, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #ef4444; font-size: 28px;">sell</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Descontos: R$ {{ number_format($totalDiscount, 2, ',', '.') }} | Cashback: R$ {{ number_format($totalCashbackActive, 2, ',', '.') }}</p>
            </div>

            <!-- Net Profit -->
            @php
                $netProfit = $totalProductProfit + $shippingProfit - $totalDiscount - $totalCashbackActive;
            @endphp
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Lucro Operacional</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: {{ $netProfit >= 0 ? '#8b5cf6' : '#ef4444' }}; margin: 8px 0 0 0;">R$ {{ number_format($netProfit, 2, ',', '.') }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: {{ $netProfit >= 0 ? '#ede9fe' : '#fee2e2' }}; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: {{ $netProfit >= 0 ? '#8b5cf6' : '#ef4444' }}; font-size: 28px;">account_balance</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Após custos e benefícios</p>
            </div>

            <!-- Net Margin -->
            @php
                $netMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
            @endphp
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <p style="color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; margin: 0;">Margem Líquida</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: {{ $netMargin >= 20 ? '#10b981' : ($netMargin >= 10 ? '#f59e0b' : '#ef4444') }}; margin: 8px 0 0 0;">{{ number_format($netMargin, 1, ',', '.') }}%</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: {{ $netMargin >= 20 ? '#d1fae5' : ($netMargin >= 10 ? '#fef3c7' : '#fee2e2') }}; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: {{ $netMargin >= 20 ? '#10b981' : ($netMargin >= 10 ? '#f59e0b' : '#ef4444') }}; font-size: 28px;">percent</span>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin: 0;">Lucro sobre receita total</p>
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

        <!-- Shipping Analysis Section -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">
                <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 20px; color: #f59e0b;">local_shipping</span>
                Análise de Frete
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Frete Recebido dos Clientes -->
                <div style="padding: 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">payments</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">FRETE RECEBIDO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalShippingRevenue, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Valor pago pelos clientes</p>
                </div>

                <!-- Custo de Frete -->
                <div style="padding: 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">receipt_long</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">CUSTO DE FRETE</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalShippingCost, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Custo com transportadora</p>
                </div>

                <!-- Lucro com Frete -->
                <div style="padding: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">trending_up</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">LUCRO COM FRETE</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($shippingProfit, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">
                        @if($totalShippingRevenue > 0)
                            Margem: {{ number_format(($shippingProfit / $totalShippingRevenue) * 100, 1, ',', '.') }}%
                        @else
                            Margem: 0%
                        @endif
                    </p>
                </div>

                <!-- Pedidos com Frete -->
                <div style="padding: 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">inventory_2</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">PEDIDOS COM FRETE</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">{{ $ordersWithShipping }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">
                        @if($totalOrders > 0)
                            {{ number_format(($ordersWithShipping / $totalOrders) * 100, 1, ',', '.') }}% do total
                        @else
                            0% do total
                        @endif
                    </p>
                </div>
            </div>

            <!-- Médias de Frete -->
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0;">Valores Médios por Pedido</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">FRETE MÉDIO RECEBIDO</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #f59e0b; font-weight: 700;">R$ {{ number_format($averageShippingRevenue, 2, ',', '.') }}</p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">CUSTO MÉDIO DE FRETE</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #ef4444; font-weight: 700;">R$ {{ number_format($averageShippingCost, 2, ',', '.') }}</p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">LUCRO MÉDIO POR FRETE</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #10b981; font-weight: 700;">R$ {{ number_format($averageShippingRevenue - $averageShippingCost, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Top Estados com Mais Envios -->
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-top: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0;">
                    <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px; color: #3b82f6;">location_on</span>
                    Top 5 Estados com Mais Envios
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: white; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 10px; text-align: left; color: #6b7280; font-weight: 600; font-size: 11px;">ESTADO</th>
                            <th style="padding: 10px; text-align: center; color: #6b7280; font-weight: 600; font-size: 11px;">PEDIDOS</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">RECEITA</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">CUSTO</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">LUCRO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topShippingStates as $state)
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: white;">
                            <td style="padding: 12px; color: #1f2937; font-weight: 600; font-size: 13px;">
                                <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px;">
                                    {{ $state->state }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center; color: #1f2937; font-size: 13px; font-weight: 600;">
                                {{ $state->total_orders }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #f59e0b; font-weight: 600; font-size: 13px;">
                                R$ {{ number_format($state->total_revenue, 2, ',', '.') }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #ef4444; font-weight: 600; font-size: 13px;">
                                R$ {{ number_format($state->total_cost, 2, ',', '.') }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #10b981; font-weight: 600; font-size: 13px;">
                                R$ {{ number_format($state->total_revenue - $state->total_cost, 2, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center; color: #6b7280; font-size: 13px;">
                                Nenhum envio registrado ainda
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cashback Analysis Section -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">
                <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 20px; color: #8b5cf6;">card_giftcard</span>
                Análise de Cashback
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Total Cashback Concedido -->
                <div style="padding: 20px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">card_giftcard</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">TOTAL CONCEDIDO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalCashbackGranted, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Cashback total gerado</p>
                </div>

                <!-- Cashback Ativo -->
                <div style="padding: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">check_circle</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">CASHBACK ATIVO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalCashbackActive, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Em conta dos clientes</p>
                </div>

                <!-- Cashback Estornado -->
                <div style="padding: 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">cancel</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">CASHBACK ESTORNADO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalCashbackRefunded, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Cancelamentos e devoluções</p>
                </div>

                <!-- Pedidos com Cashback -->
                <div style="padding: 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">receipt_long</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">PEDIDOS COM CASHBACK</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">{{ $ordersWithCashback }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">
                        @if($totalOrders > 0)
                            {{ number_format(($ordersWithCashback / $totalOrders) * 100, 1, ',', '.') }}% do total
                        @else
                            0% do total
                        @endif
                    </p>
                </div>
            </div>

            <!-- Médias e Detalhes -->
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0;">Valores Médios e Impacto</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">CASHBACK MÉDIO POR PEDIDO</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #8b5cf6; font-weight: 700;">R$ {{ number_format($averageCashback, 2, ',', '.') }}</p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">DESPESA TOTAL COM CASHBACK</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #ef4444; font-weight: 700;">R$ {{ number_format($totalCashbackActive, 2, ',', '.') }}</p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">IMPACTO NA RECEITA</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #f59e0b; font-weight: 700;">
                            @if($totalRevenue > 0)
                                {{ number_format(($totalCashbackActive / $totalRevenue) * 100, 2, ',', '.') }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Discount Analysis Section -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">
                <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 20px; color: #f59e0b;">sell</span>
                Análise de Descontos (Forma de Pagamento)
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Total Descontos Concedidos -->
                <div style="padding: 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">sell</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">TOTAL CONCEDIDO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalPaymentDiscount, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Descontos totais gerados</p>
                </div>

                <!-- Descontos Ativos -->
                <div style="padding: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">check_circle</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">DESCONTOS ATIVOS</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalPaymentDiscountActive, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Em pedidos entregues</p>
                </div>

                <!-- Descontos Estornados -->
                <div style="padding: 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">cancel</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">DESCONTOS ESTORNADOS</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalPaymentDiscountRefunded, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Cancelamentos e devoluções</p>
                </div>

                <!-- Pedidos com Desconto -->
                <div style="padding: 20px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">receipt_long</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">PEDIDOS COM DESCONTO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">{{ $ordersWithPaymentDiscount }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">
                        @if($totalOrders > 0)
                            {{ number_format(($ordersWithPaymentDiscount / $totalOrders) * 100, 1, ',', '.') }}% do total
                        @else
                            0% do total
                        @endif
                    </p>
                </div>
            </div>

            <!-- Médias e Detalhes -->
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0;">Valores Médios e Impacto</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">DESCONTO MÉDIO POR PEDIDO</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #f59e0b; font-weight: 700;">R$ {{ number_format($averagePaymentDiscount, 2, ',', '.') }}</p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">RECEITA PERDIDA</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #ef4444; font-weight: 700;">R$ {{ number_format($totalPaymentDiscountActive, 2, ',', '.') }}</p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">IMPACTO NA RECEITA</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #f59e0b; font-weight: 700;">
                            @if($totalRevenue > 0)
                                {{ number_format((($totalPaymentDiscountActive / ($totalRevenue + $totalPaymentDiscountActive)) * 100), 2, ',', '.') }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                    <div style="text-align: center; padding: 12px; background: white; border-radius: 6px;">
                        <p style="margin: 0; font-size: 11px; color: #6b7280; font-weight: 600;">RECEITA POTENCIAL</p>
                        <p style="margin: 8px 0 0 0; font-size: 20px; color: #10b981; font-weight: 700;">R$ {{ number_format($totalRevenue + $totalPaymentDiscountActive, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Descontos por Forma de Pagamento -->
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-top: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0;">
                    <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px; color: #f59e0b;">credit_card</span>
                    Descontos por Forma de Pagamento
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: white; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 10px; text-align: left; color: #6b7280; font-weight: 600; font-size: 11px;">FORMA DE PAGAMENTO</th>
                            <th style="padding: 10px; text-align: center; color: #6b7280; font-weight: 600; font-size: 11px;">PEDIDOS</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">TOTAL DESCONTOS</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">DESCONTO MÉDIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discountsByPaymentMethod as $method)
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: white;">
                            <td style="padding: 12px; color: #1f2937; font-weight: 600; font-size: 13px;">
                                <span style="background-color: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px;">
                                    {{ $method->payment_method_name }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center; color: #1f2937; font-size: 13px; font-weight: 600;">
                                {{ $method->total_orders }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #f59e0b; font-weight: 700; font-size: 14px;">
                                R$ {{ number_format($method->total_discount, 2, ',', '.') }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 13px;">
                                R$ {{ number_format($method->average_discount, 2, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #6b7280; font-size: 13px;">
                                Nenhum desconto registrado ainda
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product Analysis Section -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0;">
                <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 20px; color: #8b5cf6;">inventory</span>
                Análise de Produtos (Custo x Venda)
            </h3>
            
            <!-- Métricas Gerais de Produtos -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Receita Total de Produtos -->
                <div style="padding: 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">payments</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">RECEITA TOTAL</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalProductRevenue, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Vendas de produtos</p>
                </div>

                <!-- Custo Total -->
                <div style="padding: 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">trending_down</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">CUSTO TOTAL</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalProductCost ?? 0, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Custo dos produtos vendidos</p>
                </div>

                <!-- Lucro Bruto -->
                <div style="padding: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">trending_up</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">LUCRO BRUTO</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">R$ {{ number_format($totalProductProfit, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Receita - Custo</p>
                </div>

                <!-- Margem Média -->
                <div style="padding: 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 8px; color: white;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;">percent</span>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 11px; opacity: 0.9; font-weight: 600;">MARGEM MÉDIA</p>
                            <p style="margin: 4px 0 0 0; font-size: 22px; font-weight: 700;">{{ number_format($averageProfitMargin, 1, ',', '.') }}%</p>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: 11px; opacity: 0.8;">Margem de lucro média</p>
                </div>
            </div>

            <!-- Tabela de Produtos com Análise Detalhada -->
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0 0 16px 0;">
                    <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px; color: #8b5cf6;">analytics</span>
                    Top 10 Produtos - Performance e Rentabilidade
                </h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: white; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 10px; text-align: left; color: #6b7280; font-weight: 600; font-size: 11px;">PRODUTO</th>
                            <th style="padding: 10px; text-align: center; color: #6b7280; font-weight: 600; font-size: 11px;">QTD</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">PREÇO</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">CUSTO</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">MARGEM</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">RECEITA</th>
                            <th style="padding: 10px; text-align: right; color: #6b7280; font-weight: 600; font-size: 11px;">LUCRO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productAnalysis as $product)
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: white;">
                            <td style="padding: 12px; color: #1f2937; font-weight: 600; font-size: 13px;">
                                {{ Str::limit($product->name, 30) }}
                            </td>
                            <td style="padding: 12px; text-align: center; color: #1f2937; font-size: 13px; font-weight: 600;">
                                <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px;">
                                    {{ $product->total_quantity }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #ef4444; font-weight: 600; font-size: 12px;">
                                R$ {{ number_format($product->cost_price, 2, ',', '.') }}
                            </td>
                            <td style="padding: 12px; text-align: right; font-weight: 700; font-size: 13px;">
                                <span style="
                                    padding: 4px 8px; 
                                    border-radius: 4px;
                                    background-color: {{ $product->profit_margin_percentage >= 30 ? '#dcfce7' : ($product->profit_margin_percentage >= 15 ? '#fef3c7' : '#fee2e2') }};
                                    color: {{ $product->profit_margin_percentage >= 30 ? '#15803d' : ($product->profit_margin_percentage >= 15 ? '#92400e' : '#991b1b') }};
                                ">
                                    {{ number_format($product->profit_margin_percentage, 1, ',', '.') }}%
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: right; color: #3b82f6; font-weight: 700; font-size: 13px;">
                                R$ {{ number_format($product->total_revenue, 2, ',', '.') }}
                            </td>
                            <td style="padding: 12px; text-align: right; color: #10b981; font-weight: 700; font-size: 13px;">
                                R$ {{ number_format($product->total_revenue - $product->total_cost, 2, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280; font-size: 13px;">
                                Nenhum produto vendido ainda
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
