@extends('shop.layout')

@section('title', 'Pedido Confirmado')

@section('content')
    <div class="container" style="padding: 60px 20px;">
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <!-- Success Icon -->
            <div style="margin-bottom: 30px;">
                <div style="width: 80px; height: 80px; margin: 0 auto 20px; background-color: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981;"></i>
                </div>
            </div>

            <h1 style="font-size: 36px; color: var(--text-dark); margin-bottom: 15px;">Pedido Confirmado!</h1>
            <p style="font-size: 18px; color: var(--text-light); margin-bottom: 30px;">Seu pedido foi realizado com sucesso.</p>

            <!-- Order Details -->
            <div style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; text-align: left;">
                <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                    <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Número do Pedido</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--primary-color);">{{ $order->order_number }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Data do Pedido</div>
                        <div style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Status</div>
                        <div style="font-size: 16px; font-weight: 600; color: #f59e0b;">
                            <i class="fas fa-clock"></i> Pendente
                        </div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Email de Confirmação</div>
                    <div style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->customer->email }}</div>
                </div>
            </div>

            <!-- Billing Address -->
            <div style="background-color: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; text-align: left;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px;">Endereço de Cobrança</h3>
                @if($order->billingAddress)
                    <p style="color: var(--text-light); line-height: 1.8;">
                        {{ $order->customer->name }}<br>
                        {{ $order->billingAddress->street }}<br>
                        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} - {{ $order->billingAddress->postal_code }}
                    </p>
                @else
                    <p style="color: var(--text-light);">Endereço de cobrança não disponível</p>
                @endif
            </div>

            <!-- Shipping Address -->
            <div style="background-color: var(--bg-light); border-radius: 8px; padding: 25px; margin-bottom: 30px; text-align: left;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px;">Endereço de Entrega</h3>
                @if($order->shippingAddress)
                    <p style="color: var(--text-light); line-height: 1.8;">
                        {{ $order->customer->name }}<br>
                        {{ $order->shippingAddress->street }}<br>
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} - {{ $order->shippingAddress->postal_code }}
                    </p>
                @else
                    <p style="color: var(--text-light);">Endereço de entrega não disponível</p>
                @endif
            </div>

            <!-- Order Items -->
            <div style="background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <div style="background-color: var(--bg-light); padding: 15px; font-weight: 700; color: var(--text-dark); text-align: left;">
                    Itens do Pedido
                </div>
                @foreach($order->items as $item)
                    <div style="padding: 15px; border-bottom: 1px solid var(--border-color); text-align: left; display: grid; grid-template-columns: 1fr auto auto; gap: 20px; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">{{ $item->product_name }}</div>
                            <div style="font-size: 14px; color: var(--text-light);">Quantidade: {{ $item->quantity }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 14px; color: var(--text-light);">R$ {{ number_format($item->product_price, 2, ',', '.') }}</div>
                        </div>
                        <div style="text-align: right; font-weight: 700; color: var(--primary-color);">
                            R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                        </div>
                    </div>
                @endforeach

                <div style="padding: 20px; background-color: var(--bg-light); text-align: right;">
                    <div style="display: grid; grid-template-columns: auto auto; gap: 40px; justify-content: flex-end; margin-bottom: 15px;">
                        <div>
                            <div style="font-size: 14px; color: var(--text-light);">Subtotal:</div>
                            <div style="font-weight: 600; color: var(--text-dark);">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 14px; color: var(--text-light);">Frete:</div>
                            <div style="font-weight: 600; color: var(--text-dark);">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</div>
                        </div>
                    </div>
                    <div style="border-top: 2px solid var(--border-color); padding-top: 15px; display: grid; grid-template-columns: auto auto; gap: 40px; justify-content: flex-end;">
                        <div>
                            <div style="font-size: 14px; color: var(--text-light);">Total:</div>
                            <div style="font-size: 24px; font-weight: 700; color: var(--primary-color);">R$ {{ number_format($order->total, 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div style="background-color: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; text-align: left;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px;">Método de Pagamento</h3>
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
                    @endswitch
                </div>
            </div>

            <!-- Next Steps -->
            <div style="background-color: #eff6ff; border-left: 4px solid var(--primary-color); padding: 20px; border-radius: 4px; margin-bottom: 30px; text-align: left;">
                <h3 style="font-weight: 700; color: var(--primary-color); margin-bottom: 10px;">Próximos Passos</h3>
                <ul style="margin-left: 20px; color: var(--text-light);">
                    <li style="margin-bottom: 8px;">Você receberá um email de confirmação em {{ $order->customer_email }}</li>
                    <li style="margin-bottom: 8px;">Após a confirmação do pagamento, seu pedido será processado</li>
                    <li>Você receberá atualizações sobre o envio por email e SMS</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="padding: 15px; text-align: center; display: block;">
                    <i class="fas fa-shopping-bag"></i> Continuar Comprando
                </a>
                <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="padding: 15px; text-align: center; display: block;">
                    <i class="fas fa-home"></i> Voltar à Página Inicial
                </a>
            </div>
        </div>
    </div>
@endsection
