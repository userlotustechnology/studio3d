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
        .status-banner {
            background: linear-gradient(135deg, #f0f5fe 0%, #e8f0fd 100%);
            border-left: 4px solid #0f79f3;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .status-banner p {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        .status-label {
            display: inline-block;
            background-color: #0f79f3;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
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
                <h1>📦 Atualização do Pedido</h1>
                <p>Seu pedido foi atualizado</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p>Olá <strong>{{ $order->customer?->name ?? 'Cliente' }}</strong>,</p>
                <p style="margin-top: 15px; margin-bottom: 20px;">Sua compra recebeu uma atualização importante. Confira o novo status abaixo:</p>

                <!-- Status Update Banner -->
                <div class="status-banner">
                    <p>Status do Pedido: <br></p>
                    <span class="status-label">
                        @switch($newStatus)
                            @case('pending')
                                ⏳ Pendente
                                @break
                            @case('processing')
                                ⚙️ Processando
                                @break
                            @case('shipped')
                                🚚 Enviado
                                @break
                            @case('delivered')
                                ✓ Entregue
                                @break
                            @case('cancelled')
                                ✕ Cancelado
                                @break
                            @default
                                {{ $newStatus }}
                        @endswitch
                    </span>
                </div>

                <!-- Order Info -->
                <div class="section">
                    <div class="order-info">
                        <p><strong>Número do Pedido:</strong> #{{ $order->order_number }}</p>
                        <p><strong>Data do Pedido:</strong> {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
                        <p><strong>Valor Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                        
                        @if($order->status === 'shipped' && $order->tracking_code && $order->shippingCompany)
                        <hr style="margin: 10px 0; border: none; border-top: 1px solid #ddd;">
                        <p><strong>Código de Rastreio:</strong> <span style="font-family: monospace; font-weight: 700; color: #0f79f3;">{{ $order->tracking_code }}</span></p>
                        <p><strong>Transportadora:</strong> {{ $order->shippingCompany->name }}</p>
                        @if($order->getTrackingUrl())
                        <p><a href="{{ $order->getTrackingUrl() }}" target="_blank" style="color: #0f79f3; text-decoration: underline;">Acompanhar encomenda →</a></p>
                        @endif
                        @endif
                    </div>
                </div>

                <!-- Products -->
                <div class="section">
                    <h2>Produtos</h2>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%;">Produto</th>
                                <th style="width: 15%; text-align: center;">Qtd</th>
                                <th style="width: 35%; text-align: right;">Preço Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td style="text-align: center;">{{ $item->quantity }}</td>
                                <td style="text-align: right;">R$ {{ number_format($item->product_price * $item->quantity, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Shipping Address -->
                <div class="section">
                    <h2>Endereço de Entrega</h2>
                    <div class="address-block">
                        <p>{{ $order->shippingAddress?->street ?? 'Não informado' }}<br>
                        {{ $order->shippingAddress?->number ?? '' }}{{ $order->shippingAddress?->complement ? ', ' . $order->shippingAddress->complement : '' }}<br>
                        {{ $order->shippingAddress?->city ?? '' }} - {{ $order->shippingAddress?->state ?? '' }}<br>
                        CEP: {{ $order->shippingAddress?->postal_code ?? '' }}</p>
                    </div>
                </div>

                <!-- CTA Button -->
                <div style="text-align: center;">
                    <a href="{{ route('order.track', ['orderNumber' => $order->order_number]) }}" class="cta-button">Acompanhar Pedido</a>
                </div>


                <div class="divider"></div>

                <p style="font-size: 14px; color: #666;">Você receberá notificações sobre todas as atualizações do seu pedido. Qualquer dúvida, entre em contato conosco.</p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-brand">{{ config('app.name') }}</div>
                <p>Obrigado por sua confiança!</p>
            </div>
        </div>
    </div>
</body>
</html>
