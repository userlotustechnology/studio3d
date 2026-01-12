@extends('shop.layout')

@section('title', 'Acompanhar Pedido')

@section('content')
    <div class="container" style="padding: 60px 20px;">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 32px; color: var(--text-dark); margin-bottom: 10px;">Acompanhamento do Pedido</h1>
                <p style="font-size: 16px; color: var(--text-light);">Pedido <strong>{{ $order->order_number }}</strong></p>
            </div>

            <!-- Order Status Timeline -->
            <div style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h2 style="font-size: 18px; color: var(--text-dark); margin-bottom: 20px;">Status do Pedido</h2>
                
                <div style="position: relative; padding: 20px 0;">
                    @php
                        $statuses = [
                            'pending' => ['label' => 'Pendente', 'icon' => '⏳', 'color' => '#f59e0b'],
                            'processing' => ['label' => 'Processando', 'icon' => '⚙️', 'color' => '#3b82f6'],
                            'shipped' => ['label' => 'Enviado', 'icon' => '🚚', 'color' => '#0f79f3'],
                            'delivered' => ['label' => 'Entregue', 'icon' => '✓', 'color' => '#10b981'],
                            'cancelled' => ['label' => 'Cancelado', 'icon' => '✕', 'color' => '#ef4444'],
                        ];
                        
                        $currentStatus = $order->status;
                        $statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
                        $currentIndex = array_search($currentStatus, $statusOrder);
                        
                        // Se está cancelado, mostrar diferente
                        if ($currentStatus === 'cancelled') {
                            $currentIndex = -1;
                        }
                    @endphp

                    @foreach($statusOrder as $index => $status)
                        <div style="display: flex; margin-bottom: 20px;">
                            <!-- Timeline dot -->
                            <div style="position: relative; width: 50px; text-align: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ ($index <= $currentIndex && $currentIndex >= 0) ? $statuses[$status]['color'] : '#e5e7eb' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 18px;">
                                    {{ $statuses[$status]['icon'] }}
                                </div>
                                @if($index < count($statusOrder) - 1)
                                    <div style="width: 2px; height: 30px; background-color: {{ ($index < $currentIndex && $currentIndex >= 0) ? $statuses[$status]['color'] : '#e5e7eb' }}; margin: 0 auto; margin-top: 5px;"></div>
                                @endif
                            </div>
                            
                            <!-- Status label -->
                            <div style="margin-left: 20px; padding-top: 8px;">
                                <p style="margin: 0; font-weight: 600; font-size: 16px; color: {{ ($index <= $currentIndex && $currentIndex >= 0) ? $statuses[$status]['color'] : '#999' }};">
                                    {{ $statuses[$status]['label'] }}
                                </p>
                                @if($index <= $currentIndex && $currentIndex >= 0)
                                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #999;">Concluído</p>
                                @else
                                    <p style="margin: 5px 0 0 0; font-size: 13px; color: #999;">Aguardando</p>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($currentStatus === 'cancelled')
                    <div style="display: flex; margin-bottom: 20px;">
                        <div style="position: relative; width: 50px; text-align: center;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 18px;">
                                ✕
                            </div>
                        </div>
                        <div style="margin-left: 20px; padding-top: 8px;">
                            <p style="margin: 0; font-weight: 600; font-size: 16px; color: #ef4444;">Cancelado</p>
                            <p style="margin: 5px 0 0 0; font-size: 13px; color: #999;">Pedido cancelado</p>
                        </div>
                    </div>
                    @endif
                </div>

                @php
                    $statusService = app(\App\Services\OrderStatusTransitionService::class);
                    $history = $statusService->getFormattedHistory($order);
                @endphp

                @if(count($history) > 0)
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px;">📋 Histórico de Mudanças</h3>
                    <div style="space-y: 0;">
                        @foreach($history as $change)
                        <div style="padding: 12px; margin-bottom: 10px; background-color: #fafafa; border-left: 4px solid {{ $change['color'] }}; border-radius: 4px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                                <div style="flex: 1;">
                                    <p style="margin: 0 0 5px 0; font-weight: 600; font-size: 14px; color: var(--text-dark);">
                                        {{ $change['icon'] }} {{ $change['from_label'] }} → {{ $change['to_label'] }}
                                    </p>
                                    <p style="margin: 0 0 5px 0; font-size: 12px; color: var(--text-light);">
                                        {{ $change['created_at_formatted'] }}
                                    </p>
                                    @if($change['reason'])
                                    <p style="margin: 0; font-size: 12px; color: #666; font-style: italic;">
                                        {{ $change['reason'] }}
                                    </p>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <span style="display: inline-block; padding: 4px 8px; background-color: {{ $change['color'] }}; color: white; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        @if($change['changed_by'] === 'system')
                                            Sistema
                                        @elseif($change['changed_by'] === 'admin')
                                            Admin
                                        @elseif($change['changed_by'] === 'command')
                                            CLI
                                        @else
                                            {{ $change['changed_by'] }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Details -->
            <div style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h2 style="font-size: 18px; color: var(--text-dark); margin-bottom: 20px;">Detalhes do Pedido</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <p style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Data do Pedido</p>
                        <p style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Valor Total</p>
                        <p style="font-size: 16px; font-weight: 600; color: var(--primary-color);">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <p style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Método de Pagamento</p>
                        <p style="font-size: 16px; font-weight: 600; color: var(--text-dark);">
                            @switch($order->payment_method)
                                @case('credit_card')
                                    💳 Cartão de Crédito
                                    @break
                                @case('debit_card')
                                    💳 Cartão de Débito
                                    @break
                                @case('pix')
                                    📱 PIX
                                    @break
                                @case('boleto')
                                    📋 Boleto
                                    @break
                                @case('paypal')
                                    🅿️ PayPal
                                    @break
                                @default
                                    {{ $order->payment_method }}
                            @endswitch
                        </p>
                    </div>
                    <div>
                        <p style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Cliente</p>
                        <p style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->customer->name }}</p>
                    </div>
                </div>

                @if($order->status === 'shipped' && $order->tracking_code && $order->shippingCompany)
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px;">🚚 Informações de Rastreio</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <p style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Código de Rastreio</p>
                            <p style="font-size: 16px; font-weight: 700; color: var(--primary-color); font-family: monospace;">{{ $order->tracking_code }}</p>
                        </div>
                        <div>
                            <p style="font-size: 13px; color: var(--text-light); margin-bottom: 5px;">Transportadora</p>
                            <p style="font-size: 16px; font-weight: 600; color: var(--text-dark);">{{ $order->shippingCompany->name }}</p>
                        </div>
                    </div>
                    @if($order->getTrackingUrl())
                    <a href="{{ $order->getTrackingUrl() }}" target="_blank" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: opacity 0.3s;">
                        Rastrear Encomenda →
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div style="background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <div style="background-color: var(--bg-light); padding: 15px; font-weight: 700; color: var(--text-dark);">
                    Itens do Pedido
                </div>
                @foreach($order->items as $item)
                    <div style="padding: 15px; border-bottom: 1px solid var(--border-color); display: grid; grid-template-columns: 1fr auto auto; gap: 20px; align-items: center;">
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

            <!-- Delivery Address -->
            @if($order->shippingAddress)
            <div style="background-color: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 15px;">Endereço de Entrega</h3>
                <p style="color: var(--text-light); line-height: 1.8; margin: 0;">
                    {{ $order->customer->name }}<br>
                    {{ $order->shippingAddress->street }}<br>
                    {{ $order->shippingAddress->number }}{{ $order->shippingAddress->complement ? ', ' . $order->shippingAddress->complement : '' }}<br>
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
                    CEP: {{ $order->shippingAddress->postal_code }}
                </p>
            </div>
            @endif

            <!-- Support Info -->
            <div style="background-color: #eff6ff; border-left: 4px solid var(--primary-color); padding: 20px; border-radius: 4px; text-align: center;">
                <p style="margin: 0; font-size: 14px; color: var(--text-light);">
                    Dúvidas sobre seu pedido? Entre em contato conosco através do email ou WhatsApp.
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="display: grid; gap: 15px; margin-top: 30px;">
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
                <a href="{{ $whatsappUrl }}" target="_blank" style="padding: 15px; text-align: center; display: block; background-color: #25d366; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background-color 0.3s;">
                    <i class="fab fa-whatsapp"></i> Falar no WhatsApp
                </a>
                @endif
                <a href="{{ route('orders.search-form') }}" style="padding: 15px; text-align: center; display: block; background-color: var(--bg-light); color: var(--primary-color); text-decoration: none; border-radius: 6px; font-weight: 600;">
                    <i class="fas fa-search"></i> Consultar outro pedido
                </a>
                <a href="{{ route('shop.index') }}" style="padding: 15px; text-align: center; display: block; background-color: var(--primary-color); color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    <i class="fas fa-shopping-bag"></i> Continuar comprando
                </a>
            </div>
        </div>
    </div>
@endsection
