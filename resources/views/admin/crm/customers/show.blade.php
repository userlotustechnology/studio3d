@extends('layouts.main')

@section('title', $customer->name . ' - CRM de Clientes')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px; min-height: 100vh;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">{{ $customer->name }}</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">
                    <i class="fas fa-envelope"></i> {{ $customer->email }} | 
                    <i class="fas fa-phone"></i> {{ $customer->phone ?? 'Sem telefone' }} | 
                    <i class="fas fa-id-card"></i> {{ $customer->cpf }}
                </p>
            </div>
            <a href="{{ route('admin.crm.customers.index') }}" style="background-color: #6b7280; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <!-- Cards de Estatísticas Principais -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Total Gasto</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($statistics['total_spent'], 2, ',', '.') }}</h3>
                <i class="fas fa-money-bill-wave" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Ticket Médio</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($statistics['average_ticket'], 2, ',', '.') }}</h3>
                <i class="fas fa-chart-line" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Total de Pedidos</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">{{ $statistics['total_orders'] }}</h3>
                <i class="fas fa-shopping-cart" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Total de Itens</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">{{ $statistics['total_items'] }}</h3>
                <i class="fas fa-box" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>

            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="margin: 0 0 8px 0; font-size: 12px; opacity: 0.9;">Saldo Cashback</p>
                <h3 style="margin: 0; font-size: 28px; font-weight: 700;">R$ {{ number_format($customer->cashback_balance ?? 0, 2, ',', '.') }}</h3>
                <i class="fas fa-gift" style="font-size: 40px; opacity: 0.3; position: absolute; right: 24px; top: 24px;"></i>
            </div>
        </div>

        <!-- Detalhes do Cashback -->
        <div style="background: linear-gradient(135deg, #f5e6ff 0%, #ede9fe 100%); border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px; border-left: 4px solid #8b5cf6;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div>
                    <p style="color: #6d28d9; font-size: 12px; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;">💰 Cashback Total Acumulado</p>
                    <p style="color: #8b5cf6; font-size: 28px; font-weight: 700; margin: 0;">R$ {{ number_format($customer->cashback_balance ?? 0, 2, ',', '.') }}</p>
                    <p style="color: #6b7280; font-size: 11px; margin: 4px 0 0 0;">Disponível para próximas compras</p>
                </div>
                <div>
                    <p style="color: #6d28d9; font-size: 12px; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;">📊 Cashback por Compra (Média)</p>
                    @php
                        $averageCashbackPerOrder = $statistics['total_orders'] > 0 
                            ? ($customer->cashback_balance ?? 0) / $statistics['total_orders']
                            : 0;
                    @endphp
                    <p style="color: #8b5cf6; font-size: 28px; font-weight: 700; margin: 0;">R$ {{ number_format($averageCashbackPerOrder, 2, ',', '.') }}</p>
                    <p style="color: #6b7280; font-size: 11px; margin: 4px 0 0 0;">Média de cashback por pedido</p>
                </div>
                <div>
                    <p style="color: #6d28d9; font-size: 12px; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;">🎯 Taxa Média de Cashback</p>
                    @php
                        $averageCashbackRate = $statistics['total_spent'] > 0 
                            ? (($customer->cashback_balance ?? 0) / $statistics['total_spent']) * 100
                            : 0;
                    @endphp
                    <p style="color: #8b5cf6; font-size: 28px; font-weight: 700; margin: 0;">{{ number_format($averageCashbackRate, 2) }}%</p>
                    <p style="color: #6b7280; font-size: 11px; margin: 4px 0 0 0;">Da compra retorna em cashback</p>
                </div>
            </div>
            <p style="color: #6d28d9; font-size: 12px; margin: 16px 0 0 0; padding-top: 16px; border-top: 1px solid #d8b4fe; line-height: 1.6;">
                <i class="fas fa-info-circle"></i>
                O cashback pode ser utilizado como crédito em compras futuras. Quanto mais o cliente compra, mais cashback acumula! Esse é um excelente incentivo para fidelização.
            </p>
        </div>

        <!-- Análises em Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Padrão de Compras -->
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                    <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                        <i class="fas fa-chart-pie"></i> Padrão de Compras
                    </h5>
                </div>
                <div style="padding: 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 0; color: #6b7280;"><strong>Frequência:</strong></td>
                            <td style="padding: 12px 0; text-align: right; color: #1f2937;">{{ $purchasePattern['frequency'] }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 0; color: #6b7280;"><strong>Trend:</strong></td>
                            <td style="padding: 12px 0; text-align: right;">
                                @if($purchasePattern['trend'] === 'increasing')
                                    <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        <i class="fas fa-arrow-up"></i> Crescente
                                    </span>
                                @elseif($purchasePattern['trend'] === 'decreasing')
                                    <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        <i class="fas fa-arrow-down"></i> Decrescente
                                    </span>
                                @else
                                    <span style="background-color: #e5e7eb; color: #374151; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        <i class="fas fa-equals"></i> Estável
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 0; color: #6b7280;"><strong>Últimos 30 dias:</strong></td>
                            <td style="padding: 12px 0; text-align: right; color: #1f2937;">{{ $purchasePattern['last_30_days'] }} compra(s)</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 0; color: #6b7280;"><strong>Últimos 90 dias:</strong></td>
                            <td style="padding: 12px 0; text-align: right; color: #1f2937;">{{ $purchasePattern['last_90_days'] }} compra(s)</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 0; color: #6b7280;"><strong>Último ano:</strong></td>
                            <td style="padding: 12px 0; text-align: right; color: #1f2937;">{{ $purchasePattern['last_year'] }} compra(s)</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #6b7280;"><strong>Dias entre compras (média):</strong></td>
                            <td style="padding: 12px 0; text-align: right; color: #1f2937;">{{ number_format($purchasePattern['days_between_purchases'], 1, ',', '.') }} dias</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Status dos Pedidos -->
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                    <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                        <i class="fas fa-tasks"></i> Status dos Pedidos
                    </h5>
                </div>
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                        <div style="text-align: center; padding: 16px; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px;">Entregues</p>
                            <strong style="display: block; font-size: 24px; color: #10b981;">{{ $orderStatusSummary['completed'] }}</strong>
                        </div>
                        <div style="text-align: center; padding: 16px; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px;">Enviados</p>
                            <strong style="display: block; font-size: 24px; color: #3b82f6;">{{ $orderStatusSummary['shipped'] }}</strong>
                        </div>
                        <div style="text-align: center; padding: 16px; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px;">Processando</p>
                            <strong style="display: block; font-size: 24px; color: #06b6d4;">{{ $orderStatusSummary['processing'] }}</strong>
                        </div>
                        <div style="text-align: center; padding: 16px; border: 1px solid #e5e7eb; border-radius: 6px;">
                            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 12px;">Cancelados</p>
                            <strong style="display: block; font-size: 24px; color: #ef4444;">{{ $orderStatusSummary['cancelled'] }}</strong>
                        </div>
                    </div>
                    @if($returnedOrdersValue > 0)
                        <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px; font-size: 14px;">
                            <strong style="color: #92400e;">Valor em cancelamentos:</strong>
                            <span style="color: #b45309;">R$ {{ number_format($returnedOrdersValue, 2, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Produtos Comprados -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Principais Produtos -->
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                    <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                        <i class="fas fa-star"></i> Produtos Mais Comprados
                    </h5>
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 12px;">Produto</th>
                            <th style="padding: 12px 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 12px;">Qtd</th>
                            <th style="padding: 12px 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">Vezes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px 16px; font-size: 14px; color: #1f2937;">{{ $product->product_name }}</td>
                                <td style="padding: 12px 16px; text-align: center; color: #6b7280;">{{ $product->total_quantity }}</td>
                                <td style="padding: 12px 16px; text-align: right; color: #6b7280;">{{ $product->purchase_count }}x</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 20px; text-align: center; color: #6b7280;">Nenhum produto encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Produtos Mais Lucrativos -->
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                    <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                        <i class="fas fa-coins"></i> Produtos Mais Lucrativos
                    </h5>
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 12px;">Produto</th>
                            <th style="padding: 12px 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">Faturamento</th>
                            <th style="padding: 12px 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 12px;">Compras</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mostProfitableProducts as $product)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px 16px; font-size: 14px; color: #1f2937;">{{ $product->product_name }}</td>
                                <td style="padding: 12px 16px; text-align: right; color: #1f2937; font-weight: 600;">R$ {{ number_format($product->total_revenue, 2, ',', '.') }}</td>
                                <td style="padding: 12px 16px; text-align: center; color: #6b7280;">{{ $product->times_purchased }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 20px; text-align: center; color: #6b7280;">Nenhum produto encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Histórico de Cashback -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px;">
            <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                    <i class="fas fa-gift"></i> Histórico de Cashback
                </h5>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Pedido</th>
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Data</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Subtotal do Pedido</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">% Cashback</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Cashback Ganho</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalCashbackGained = 0;
                            $ordersWithCashback = $customer->orders->where('cashback_amount', '>', 0);
                        @endphp
                        @forelse($ordersWithCashback as $order)
                            @php
                                $totalCashbackGained += $order->cashback_amount;
                            @endphp
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 16px;">
                                    <a href="{{ route('admin.crm.customers.order-detail', [$customer, $order->id]) }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td style="padding: 16px; color: #6b7280;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td style="padding: 16px; text-align: right; color: #6b7280;">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                <td style="padding: 16px; text-align: center;">
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        {{ number_format($order->cashback_percentage, 2, ',', '.') }}%
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <strong style="color: #10b981;">+ R$ {{ number_format($order->cashback_amount, 2, ',', '.') }}</strong>
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    @if(in_array($order->status, ['cancelled', 'refunded']))
                                        <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            <i class="fas fa-times"></i> Estornado
                                        </span>
                                    @else
                                        <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                            <i class="fas fa-check"></i> Creditado
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 40px; text-align: center; color: #6b7280;">
                                    <i class="fas fa-gift" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Nenhum cashback concedido ainda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($ordersWithCashback->count() > 0)
                        <tfoot>
                            <tr style="background-color: #f9fafb; border-top: 2px solid #e5e7eb;">
                                <td colspan="4" style="padding: 16px; text-align: right; font-weight: 700; color: #1f2937;">Total de Cashback Concedido:</td>
                                <td style="padding: 16px; text-align: right;">
                                    <strong style="color: #10b981; font-size: 16px;">R$ {{ number_format($totalCashbackGained, 2, ',', '.') }}</strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Histórico de Compras -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                    <i class="fas fa-history"></i> Histórico de Compras
                </h5>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Pedido</th>
                            <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600;">Data</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">Itens</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Subtotal</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Frete</th>
                            <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600;">Total</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">Status</th>
                            <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                            <tr style="border-bottom: 1px solid #e5e7eb; background-color: #fafafa;">
                                <td style="padding: 16px;"><strong style="color: #1f2937;">{{ $order->order_number }}</strong></td>
                                <td style="padding: 16px; color: #6b7280;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td style="padding: 16px; text-align: center;">
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">{{ $order->items->count() }}</span>
                                </td>
                                <td style="padding: 16px; text-align: right; color: #6b7280;">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                <td style="padding: 16px; text-align: right; color: #6b7280;">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</td>
                                <td style="padding: 16px; text-align: right;"><strong style="color: #1f2937;">R$ {{ number_format($order->total, 2, ',', '.') }}</strong></td>
                                <td style="padding: 16px; text-align: center;">
                                    @switch($order->status)
                                        @case('pending')
                                            <span style="background-color: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Pendente</span>
                                            @break
                                        @case('processing')
                                            <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Processando</span>
                                            @break
                                        @case('shipped')
                                            <span style="background-color: #e0e7ff; color: #3730a3; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Enviado</span>
                                            @break
                                        @case('delivered')
                                            <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Entregue</span>
                                            @break
                                        @case('cancelled')
                                            <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Cancelado</span>
                                            @break
                                        @default
                                            <span style="background-color: #e5e7eb; color: #374151; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">{{ $order->status }}</span>
                                    @endswitch
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    <a href="{{ route('admin.crm.customers.order-detail', [$customer, $order->id]) }}" 
                                        style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 40px; text-align: center; color: #6b7280;">
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
