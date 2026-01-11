@extends('shop.layout')

@section('title', 'Detalhes do Pedido')

@section('content')
    <div class="container order-details-container" style="padding: 60px 20px;">
        <div class="order-details-content" style="max-width: 600px; margin: 0 auto;">
            <!-- Header -->
            <div class="order-header" style="display: flex; align-items: center; margin-bottom: 40px; gap: 20px;">
                <a href="{{ route('orders.search-form') }}" style="color: var(--primary-color); text-decoration: none; font-size: 16px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="order-title" style="font-size: 32px; color: var(--text-dark); margin: 0;">Detalhes do Pedido</h1>
            </div>

            <!-- Order Header Info -->
            <div class="order-card" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                    <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Número do Pedido</div>
                    <div class="order-number" style="font-size: 24px; font-weight: 700; color: var(--primary-color);">{{ $order->order_number }}</div>
                </div>

                <div class="order-meta-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Data do Pedido</div>
                        <div style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Status</div>
                        <div style="font-size: 16px; font-weight: 600;">
                            @php
                                $statusIcons = [
                                    'pending' => 'fa-clock',
                                    'processing' => 'fa-cog',
                                    'shipped' => 'fa-box',
                                    'delivered' => 'fa-check-circle',
                                    'cancelled' => 'fa-times-circle',
                                ];
                                $statusColors = [
                                    'pending' => '#f59e0b',
                                    'processing' => '#3b82f6',
                                    'shipped' => '#8b5cf6',
                                    'delivered' => '#10b981',
                                    'cancelled' => '#ef4444',
                                ];
                                $icon = $statusIcons[$order->status] ?? 'fa-info-circle';
                                $color = $statusColors[$order->status] ?? '#6b7280';
                            @endphp
                            <span style="color: {{ $color }};">
                                <i class="fas {{ $icon }}"></i> {{ translateOrderStatus($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Email de Contato</div>
                    <div style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->customer->email }}</div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="order-card" style="background-color: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px; margin-top: 0;">Informações do Cliente</h3>
                <div class="customer-info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Nome</div>
                        <div style="font-weight: 600; color: var(--text-dark);">{{ $order->customer->name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Telefone</div>
                        <div style="font-weight: 600; color: var(--text-dark);">{{ $order->customer->phone ?? 'Não informado' }}</div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">CPF</div>
                        <div style="font-weight: 600; color: var(--text-dark);">{{ $order->customer->cpf }}</div>
                    </div>
                </div>
            </div>

            <!-- Billing Address -->
            <div style="background-color: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px; margin-top: 0;">Endereço de Cobrança</h3>
                @if($order->billingAddress)
                    <p style="color: var(--text-light); line-height: 1.8; margin: 0;">
                        {{ $order->billingAddress->street }}<br>
                        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} - {{ $order->billingAddress->postal_code }}
                    </p>
                @else
                    <p style="color: var(--text-light);">Endereço não disponível</p>
                @endif
            </div>

            <!-- Shipping Address -->
            <div style="background-color: var(--bg-light); border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px; margin-top: 0;">Endereço de Entrega</h3>
                @if($order->shippingAddress)
                    <p style="color: var(--text-light); line-height: 1.8; margin: 0;">
                        {{ $order->shippingAddress->street }}<br>
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} - {{ $order->shippingAddress->postal_code }}
                    </p>
                @else
                    <p style="color: var(--text-light);">Endereço não disponível</p>
                @endif
            </div>

            <!-- Order Items -->
            <div class="order-items-card" style="background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <div style="background-color: var(--bg-light); padding: 15px; font-weight: 700; color: var(--text-dark);">
                    Itens do Pedido
                </div>
                @foreach($order->items as $item)
                    <div class="order-item" style="padding: 15px; border-bottom: 1px solid var(--border-color); text-align: left; display: grid; grid-template-columns: 1fr auto auto; gap: 20px; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">{{ $item->product_name }}</div>
                            <div style="font-size: 14px; color: var(--text-light);">Quantidade: {{ $item->quantity }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 14px; color: var(--text-light);">R$ {{ number_format($item->product_price, 2, ',', '.') }}</div>
                        </div>
                        <div style="text-align: right; font-weight: 700; color: var(--primary-color);">
                            R$ {{ number_format($item->subtotal ?? ($item->product_price * $item->quantity), 2, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <div style="padding: 20px; background-color: var(--bg-light);">
                    <div class="order-totals" style="display: grid; grid-template-columns: auto auto; gap: 40px; justify-content: flex-end; margin-bottom: 15px;">
                        <div>
                            <div style="font-size: 14px; color: var(--text-light);">Subtotal:</div>
                            <div style="font-weight: 600; color: var(--text-dark);">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-light);">Frete:</div>
                            <div style="font-weight: 600; color: var(--text-dark);">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="order-total-final" style="border-top: 2px solid var(--border-color); padding-top: 15px; display: grid; grid-template-columns: auto auto; gap: 40px; justify-content: flex-end;">
                        <div>
                            <div style="font-size: 14px; color: var(--text-light);">Total:</div>
                            <div class="total-price" style="font-size: 24px; font-weight: 700; color: var(--primary-color);">R$ {{ number_format($order->total, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div style="background-color: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px; margin-top: 0;">Método de Pagamento</h3>
                <div style="font-size: 16px; color: var(--text-dark); font-weight: 600;">
                    @switch($order->payment_method)
                        @case('credit_card')
                            <i class="fas fa-credit-card"></i> Cartão de Crédito
                            @break
                        @case('debit_card')
                            <i class="fas fa-credit-card"></i> Cartão de Débito
                            @break
                        @case('pix')
                            <i class="fas fa-qrcode"></i> PIX
                            @break
                        @case('boleto')
                            <i class="fas fa-barcode"></i> Boleto
                            @break
                        @case('paypal')
                            <i class="fab fa-paypal"></i> PayPal
                            @break
                        @default
                            {{ ucfirst($order->payment_method) }}
                    @endswitch
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons" style="display: grid; gap: 15px;">
                @if($order->customer->phone)
                <?php
                    // Remove caracteres especiais do telefone
                    $phone = preg_replace('/[^0-9]/', '', $order->customer->phone);
                    // Adiciona código do país (55 para Brasil) se não tiver
                    if (strlen($phone) === 11) {
                        $phone = '55' . $phone;
                    } elseif (strlen($phone) === 10) {
                        $phone = '55' . $phone;
                    }
                    // Monta a mensagem com status traduzido
                    $message = "Olá! Gostaria de informações sobre o pedido " . $order->order_number . ". Status atual: " . translateOrderStatus($order->status) . ". Total: R$ " . number_format($order->total, 2, ',', '.');
                    $whatsappUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                ?>
                <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-whatsapp" style="padding: 15px; text-align: center; display: block; background-color: #25d366; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background-color 0.3s;">
                    <i class="fab fa-whatsapp"></i> Falar no WhatsApp
                </a>
                @endif
                <a href="{{ route('orders.search-form') }}" class="btn btn-secondary" style="padding: 15px; text-align: center; display: block;">
                    <i class="fas fa-search"></i> Consultar outro pedido
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding: 15px; text-align: center; display: block;">
                    <i class="fas fa-shopping-bag"></i> Continuar comprando
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Order Details Page Responsive Styles */
        @media (max-width: 768px) {
            .order-details-container {
                padding: 40px 15px !important;
            }
            
            .order-header {
                flex-direction: row !important;
                gap: 15px !important;
                margin-bottom: 30px !important;
            }
            
            .order-title {
                font-size: 24px !important;
            }
            
            .order-card {
                padding: 20px !important;
            }
            
            .order-number {
                font-size: 18px !important;
                word-break: break-all;
            }
            
            .order-meta-grid,
            .customer-info-grid {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }
            
            .order-item {
                grid-template-columns: 1fr !important;
                gap: 8px !important;
            }
            
            .order-item > div:last-child {
                text-align: left !important;
            }
            
            .order-totals,
            .order-total-final {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
                justify-content: stretch !important;
            }
            
            .total-price {
                font-size: 20px !important;
            }
            
            .action-buttons a {
                padding: 12px !important;
                font-size: 14px !important;
            }
        }
        
        @media (max-width: 480px) {
            .order-title {
                font-size: 20px !important;
            }
            
            .order-card {
                padding: 15px !important;
            }
        }
    </style>
@endsection
