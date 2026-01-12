@extends('layouts.main')

@section('title', $order->order_number . ' - Detalhes do Pedido')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Pedido {{ $order->order_number }}</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">
                    Cliente: <strong>{{ $customer->name }}</strong> | 
                    Data: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                </p>
            </div>
            <a href="{{ route('admin.crm.customers.show', $customer) }}" style="background-color: #6b7280; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 20px;">
            <!-- Conteúdo Principal -->
            <div>
                <!-- Itens do Pedido -->
                <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px;">
                    <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                        <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                            <i class="fas fa-box"></i> Itens do Pedido
                        </h5>
                    </div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <th style="padding: 12px 20px; text-align: left; color: #6b7280; font-weight: 600; font-size: 12px;">Produto</th>
                                <th style="padding: 12px 20px; text-align: center; color: #6b7280; font-weight: 600; font-size: 12px;">Qtd</th>
                                <th style="padding: 12px 20px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">Preço</th>
                                <th style="padding: 12px 20px; text-align: right; color: #6b7280; font-weight: 600; font-size: 12px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 16px 20px;">
                                        <strong style="color: #1f2937;">{{ $item->product_name }}</strong>
                                        @if($item->product)
                                            <br>
                                            <small style="color: #6b7280;">ID: {{ $item->product_id }}</small>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 20px; text-align: center; color: #6b7280;">{{ $item->quantity }}</td>
                                    <td style="padding: 16px 20px; text-align: right; color: #6b7280;">R$ {{ number_format($item->product_price, 2, ',', '.') }}</td>
                                    <td style="padding: 16px 20px; text-align: right;"><strong style="color: #1f2937;">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Endereços -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                            <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                                <i class="fas fa-map-marker-alt"></i> Cobrança
                            </h5>
                        </div>
                        <div style="padding: 20px;">
                            @if($order->billingAddress)
                                <p style="margin: 0 0 8px 0; color: #1f2937; font-weight: 600;">{{ $order->billingAddress->street }}</p>
                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $order->billingAddress->number }}, {{ $order->billingAddress->complement ?? '-' }}</p>
                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $order->billingAddress->neighborhood }}</p>
                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $order->billingAddress->city }} - {{ $order->billingAddress->state }}</p>
                                <p style="margin: 0; color: #6b7280; font-size: 14px;">CEP: {{ $order->billingAddress->zipcode }}</p>
                            @else
                                <p style="color: #6b7280;">Nenhum endereço registrado</p>
                            @endif
                        </div>
                    </div>

                    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                            <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                                <i class="fas fa-truck"></i> Entrega
                            </h5>
                        </div>
                        <div style="padding: 20px;">
                            @if($order->shippingAddress)
                                <p style="margin: 0 0 8px 0; color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->street }}</p>
                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $order->shippingAddress->number }}, {{ $order->shippingAddress->complement ?? '-' }}</p>
                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $order->shippingAddress->neighborhood }}</p>
                                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $order->shippingAddress->city }} - {{ $order->shippingAddress->state }}</p>
                                <p style="margin: 0; color: #6b7280; font-size: 14px;">CEP: {{ $order->shippingAddress->zipcode }}</p>
                            @else
                                <p style="color: #6b7280;">Nenhum endereço registrado</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Histórico de Status -->
                @if($order->statusHistories->isNotEmpty())
                    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                        <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                            <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                                <i class="fas fa-history"></i> Histórico de Status
                            </h5>
                        </div>
                        <div style="padding: 20px;">
                            @foreach($order->statusHistories->sortByDesc('created_at') as $history)
                                <div style="padding: 16px 0; border-bottom: 1px solid #e5e7eb; display: flex; align-items: flex-start; gap: 16px;">
                                    <div style="background-color: #3b82f6; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <strong style="color: #1f2937; display: block;">{{ ucfirst($history->status) }}</strong>
                                        <small style="color: #6b7280;">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                        @if($history->notes)
                                            <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 13px;">{{ $history->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Direita -->
            <div>
                <!-- Resumo Financeiro -->
                <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px;">
                    <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                        <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                            <i class="fas fa-calculator"></i> Financeiro
                        </h5>
                    </div>
                    <div style="padding: 20px;">
                        <table style="width: 100%; font-size: 14px;">
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 8px 0; color: #6b7280;">Subtotal:</td>
                                <td style="padding: 8px 0; text-align: right; color: #6b7280;">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 8px 0; color: #6b7280;">Frete:</td>
                                <td style="padding: 8px 0; text-align: right; color: #6b7280;">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</td>
                            </tr>
                            @if($order->discount > 0)
                                <tr style="border-bottom: 1px solid #e5e7eb; background-color: #fee2e2;">
                                    <td style="padding: 8px 0; color: #7f1d1d;"><strong>Desconto:</strong></td>
                                    <td style="padding: 8px 0; text-align: right; color: #7f1d1d;">-R$ {{ number_format($order->discount, 2, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr style="background-color: #dbeafe;">
                                <td style="padding: 12px 0; color: #1e40af;"><strong>Total:</strong></td>
                                <td style="padding: 12px 0; text-align: right; color: #1e40af;"><strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Status e Pagamento -->
                <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px;">
                    <div style="background-color: #f9fafb; padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                        <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">
                            <i class="fas fa-info-circle"></i> Informações
                        </h5>
                    </div>
                    <div style="padding: 20px;">
                        <div style="margin-bottom: 16px;">
                            <small style="color: #6b7280; display: block; margin-bottom: 4px;">Status</small>
                            @switch($order->status)
                                @case('pending')
                                    <span style="background-color: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Pendente</span>
                                    @break
                                @case('processing')
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Processando</span>
                                    @break
                                @case('shipped')
                                    <span style="background-color: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Enviado</span>
                                    @break
                                @case('delivered')
                                    <span style="background-color: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Entregue</span>
                                    @break
                                @case('cancelled')
                                    <span style="background-color: #fee2e2; color: #7f1d1d; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Cancelado</span>
                                    @break
                                @default
                                    <span style="background-color: #e5e7eb; color: #374151; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">{{ $order->status }}</span>
                            @endswitch
                        </div>

                        <div style="margin-bottom: 16px;">
                            <small style="color: #6b7280; display: block; margin-bottom: 4px;">Pagamento</small>
                            @if($order->paid_at)
                                <span style="background-color: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Pago em {{ $order->paid_at->format('d/m/Y') }}</span>
                            @else
                                <span style="background-color: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Aguardando</span>
                            @endif
                        </div>

                        <div style="margin-bottom: 16px;">
                            <small style="color: #6b7280; display: block; margin-bottom: 4px;">Método</small>
                            <p style="margin: 0; color: #1f2937; font-weight: 600; font-size: 14px;">{{ $order->payment_method ? ucfirst($order->payment_method) : 'Não informado' }}</p>
                        </div>

                        @if($order->shippingCompany)
                            <div style="margin-bottom: 16px;">
                                <small style="color: #6b7280; display: block; margin-bottom: 4px;">Transportadora</small>
                                <p style="margin: 0; color: #1f2937; font-weight: 600; font-size: 14px;">{{ $order->shippingCompany->name }}</p>
                            </div>
                        @endif

                        @if($order->tracking_code)
                            <div style="margin-bottom: 16px; padding: 12px; background-color: #f9fafb; border-radius: 6px;">
                                <small style="color: #6b7280; display: block; margin-bottom: 4px;">Rastreamento</small>
                                <code style="color: #1f2937; font-size: 13px; word-break: break-all;">{{ $order->tracking_code }}</code>
                                @if($order->getTrackingUrl())
                                    <br>
                                    <a href="{{ $order->getTrackingUrl() }}" target="_blank" style="display: inline-block; margin-top: 8px; background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-external-link-alt"></i> Rastrear
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($order->shipped_at)
                            <div style="padding-top: 16px; border-top: 1px solid #e5e7eb;">
                                <small style="color: #6b7280;">Enviado em:</small>
                                <p style="margin: 4px 0 0 0; color: #1f2937; font-weight: 600; font-size: 14px;">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif

                        @if($order->delivered_at)
                            <div style="padding-top: 16px; border-top: 1px solid #e5e7eb;">
                                <small style="color: #6b7280;">Entregue em:</small>
                                <p style="margin: 4px 0 0 0; color: #1f2937; font-weight: 600; font-size: 14px;">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
@endsection
