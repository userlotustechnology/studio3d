<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        .wrapper {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #0f79f3 0%, #796df6 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 18px;
            color: #0f79f3;
            margin-bottom: 15px;
            border-bottom: 2px solid #0f79f3;
            padding-bottom: 10px;
            font-weight: 600;
        }
        .order-info {
            background-color: #f9f9f9;
            border-left: 4px solid #0f79f3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .order-info p {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .order-info strong {
            color: #0f79f3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f0f0f0;
            border-bottom: 2px solid #0f79f3;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #333;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        tr:hover {
            background-color: #fafafa;
        }
        .summary {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .summary-row:last-child {
            margin-bottom: 0;
        }
        .summary-row.total {
            font-size: 16px;
            font-weight: 600;
            color: #0f79f3;
            border-top: 2px solid #0f79f3;
            padding-top: 15px;
            margin-top: 15px;
        }
        .address-block {
            background-color: #f0f5fe;
            border-left: 4px solid #0f79f3;
            padding: 15px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.8;
        }
        .cta-button {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #0f79f3 0%, #796df6 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 20px;
            transition: opacity 0.3s ease;
        }
        .cta-button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f5f5f5;
            border-top: 1px solid #e0e0e0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .footer-brand {
            color: #0f79f3;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>✓ Compra Confirmada!</h1>
                <p>Obrigado por sua confiança</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p>Olá <strong>{{ $order->customer?->name ?? 'Cliente' }}</strong>,</p>
                <p style="margin-top: 15px; margin-bottom: 20px;">Sua compra foi confirmada com sucesso! Confira os detalhes do seu pedido abaixo.</p>

                <!-- Order Info -->
                <div class="section">
                    <div class="order-info">
                        <p><strong>Número do Pedido:</strong> #{{ $order->order_number }}</p>
                        <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
                        <p><strong>Status:</strong> <span style="color: #36a64f; font-weight: 600;">● Pendente</span></p>
                    </div>
                </div>

                <!-- Products -->
                <div class="section">
                    <h2>Seu Pedido</h2>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40%;">Produto</th>
                                <th style="width: 15%; text-align: center;">Qtd</th>
                                <th style="width: 22%; text-align: right;">Preço Unit.</th>
                                <th style="width: 23%; text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td style="text-align: center;">{{ $item->quantity }}</td>
                                <td style="text-align: right;">R$ {{ number_format($item->product_price, 2, ',', '.') }}</td>
                                <td style="text-align: right; font-weight: 600;">R$ {{ number_format($item->product_price * $item->quantity, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Summary -->
                    <div class="summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <strong>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Frete:</span>
                            <strong>
                                @if($order->shipping_cost == 0)
                                    <span style="color: #10b981;">GRÁTIS 🎉</span>
                                @else
                                    R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}
                                @endif
                            </strong>
                        </div>
                        <div class="summary-row total">
                            <span>Total do Pedido:</span>
                            <strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="section">
                    <h2>Endereço de Entrega</h2>
                    <div class="address-block">
                        <p>{{ $order->shippingAddress?->street ?? 'Não informado' }}<br>
                        {{ $order->shippingAddress?->number ?? '' }}{{ $order->shippingAddress?->complement ? ', ' . $order->shippingAddress->complement : '' }}<br>
                        {{ $order->shippingAddress?->neighborhood ? $order->shippingAddress->neighborhood . '<br>' : '' }}
                        {{ $order->shippingAddress?->city ?? '' }} - {{ $order->shippingAddress?->state ?? '' }}<br>
                        CEP: {{ $order->shippingAddress?->postal_code ?? '' }}</p>
                    </div>
                </div>

                <!-- Payment Method -->
                @if($order->payment_method)
                <div class="section">
                    <h2>Forma de Pagamento</h2>
                    <div class="order-info">
                        <p><strong>Método:</strong> 
                        @switch($order->payment_method)
                            @case('credit_card')
                                Cartão de Crédito
                                @break
                            @case('debit_card')
                                Cartão de Débito
                                @break
                            @case('pix')
                                PIX
                                @break
                            @case('bank_transfer')
                                Transferência Bancária
                                @break
                            @case('boleto')
                                Boleto Bancário
                                @break
                            @default
                                {{ $order->payment_method }}
                        @endswitch
                        </p>
                    </div>
                </div>
                @endif

                <!-- CTA Button -->
                <div style="text-align: center;">
                    <a href="{{ route('order.track', ['orderNumber' => $order->order_number, 'token' => $order->access_token]) }}" class="cta-button">Acompanhar Pedido</a>
                </div>

                <div class="divider"></div>

                <p style="font-size: 14px; color: #666;">Você receberá atualizações sobre seu pedido por email. Qualquer dúvida, entre em contato conosco.</p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-brand">{{ $storeName }}</div>
                <p>Obrigado por comprar conosco!</p>
            </div>
        </div>
    </div>
</body>
</html>
