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
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
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
        .section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
        }
        table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        table td {
            padding: 12px 0;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #78350f;
        }
        .warning-box {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #7f1d1d;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #0f79f3 0%, #796df6 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>Pedido Cancelado</h1>
                <p>Pedido #{{ $order->order_number }}</p>
            </div>

            <!-- Conteúdo -->
            <div class="content">
                <p style="margin-bottom: 20px;">
                    Olá <strong>{{ $order->customer->name ?? 'Cliente' }}</strong>,
                </p>

                <p style="margin-bottom: 20px; line-height: 1.8;">
                    Informamos que o seu pedido foi <strong>cancelado</strong>.
                    @if($reason)
                        <br><strong>Motivo:</strong> {{ $reason }}
                    @endif
                </p>

                <!-- Detalhes do Pedido -->
                <div class="section">
                    <h3>Detalhes do Pedido</h3>
                    <table>
                        <tr>
                            <td><strong>Número:</strong></td>
                            <td style="text-align: right;">#{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Data:</strong></td>
                            <td style="text-align: right;">{{ $order->created_at->format('d/m/Y \à\s H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td style="text-align: right;">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Frete:</strong></td>
                            <td style="text-align: right;">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr style="color: #10b981;">
                            <td><strong>Desconto:</strong></td>
                            <td style="text-align: right;">-R$ {{ number_format($order->discount, 2, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr style="font-weight: 700; font-size: 15px;">
                            <td><strong>Total:</strong></td>
                            <td style="text-align: right;">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Itens do Pedido -->
                <div class="section">
                    <h3>Itens do Pedido</h3>
                    <table>
                        @foreach($order->items as $item)
                        <tr>
                            <td style="width: 70%;">
                                <strong>{{ $item->product->name }}</strong><br>
                                <small style="color: #6b7280;">Qtd: {{ $item->quantity }} | R$ {{ number_format($item->price, 2, ',', '.') }}</small>
                            </td>
                            <td style="text-align: right;">
                                R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                <!-- Informações de Reembolso -->
                <div class="warning-box">
                    <strong>💰 Informações sobre Reembolso</strong><br><br>
                    Você receberá um reembolso de <strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong> no método de pagamento utilizado. O reembolso pode levar de 5 a 10 dias úteis para aparecer em sua conta.
                </div>

                <!-- Endereço de Envio -->
                <div class="section">
                    <h3>Endereço de Envio (Não será entregue)</h3>
                    <p style="color: #6b7280;">
                        @if($order->shippingAddress)
                            {{ $order->shippingAddress->street }}, {{ $order->shippingAddress->number }}
                            @if($order->shippingAddress->complement)
                                - {{ $order->shippingAddress->complement }}
                            @endif
                            <br>
                            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} - {{ $order->shippingAddress->postal_code }}
                        @else
                            Informação não disponível
                        @endif
                    </p>
                </div>

                <!-- Próximos Passos -->
                <div class="info-box">
                    <strong>📋 Próximos Passos</strong><br><br>
                    • Verifique seu email para confirmação<br>
                    • Acompanhe o reembolso em sua conta bancária<br>
                    • Dúvidas? Fale conosco: {{ $supportEmail }}
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Obrigado por escolher {{ $storeName }}!</p>
                <p style="margin-top: 10px; color: #6b7280;">Este é um email automático. Por favor, não responda.</p>
            </div>
        </div>
    </div>
</body>
</html>
