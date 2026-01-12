@extends('layouts.main')

@section('title', 'Detalhes do Pedido - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="{{ route('admin.orders.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">{{ $order->order_number }}</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Detalhes do pedido</p>
            </div>
            <div style="text-align: right;">
                @php
                    $statusColors = [
                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'processing' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'shipped' => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                        'delivered' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
                    ];
                    $colors = $statusColors[$order->status] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280'];
                @endphp
                <span style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 8px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">
                    {{ translateOrderStatus($order->status) }}
                </span>
            </div>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 6px; margin-bottom: 20px;">
            <p style="color: #065f46; margin: 0;">✓ {{ session('success') }}</p>
        </div>
        @endif

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <!-- Main Content -->
            <div>
                <!-- Itens -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Itens do Pedido</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 12px; text-align: left; color: #6b7280; font-weight: 600; font-size: 13px;">Produto</th>
                                <th style="padding: 12px; text-align: center; color: #6b7280; font-weight: 600; font-size: 13px;">Quantidade</th>
                                <th style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 13px;">Preço</th>
                                <th style="padding: 12px; text-align: right; color: #6b7280; font-weight: 600; font-size: 13px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 16px;">
                                    <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $item->product_name }}</p>
                                </td>
                                <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 14px;">
                                    {{ $item->quantity }}
                                </td>
                                <td style="padding: 16px; text-align: right; color: #6b7280; font-size: 14px;">
                                    R$ {{ number_format($item->product_price, 2, ',', '.') }}
                                </td>
                                <td style="padding: 16px; text-align: right; color: #1f2937; font-weight: 600; font-size: 14px;">
                                    R$ {{ number_format($item->product_price * $item->quantity, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="padding: 40px; text-align: center; color: #6b7280;">
                                    Nenhum item encontrado
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Endereço de Entrega -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Endereço de Entrega</h2>
                    @if($order->shippingAddress)
                    <div style="background-color: #f9fafb; padding: 16px; border-radius: 6px;">
                        <p style="margin: 0 0 12px 0;"><span style="color: #6b7280; font-size: 13px;">Rua:</span> <span style="color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->street }}, {{ $order->shippingAddress->number }}</span></p>
                        <p style="margin: 0 0 12px 0;"><span style="color: #6b7280; font-size: 13px;">Complemento:</span> <span style="color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->complement ?? 'N/A' }}</span></p>
                        @if($order->shippingAddress->neighborhood)
                        <p style="margin: 0 0 12px 0;"><span style="color: #6b7280; font-size: 13px;">Bairro:</span> <span style="color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->neighborhood }}</span></p>
                        @endif
                        <p style="margin: 0 0 12px 0;"><span style="color: #6b7280; font-size: 13px;">Cidade:</span> <span style="color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->city }}</span></p>
                        <p style="margin: 0 0 12px 0;"><span style="color: #6b7280; font-size: 13px;">Estado:</span> <span style="color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->state }}</span></p>
                        <p style="margin: 0;"><span style="color: #6b7280; font-size: 13px;">CEP:</span> <span style="color: #1f2937; font-weight: 600;">{{ $order->shippingAddress->postal_code }}</span></p>
                    </div>
                    @else
                    <p style="color: #6b7280;">Nenhum endereço de entrega encontrado</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Informações do Pedido -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Informações</h3>
                    
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                        <p style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">Cliente</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">{{ $order->customer?->name ?? 'N/A' }}</p>
                        <p style="color: #6b7280; font-size: 12px; margin: 8px 0 0 0;">{{ $order->customer?->email ?? 'N/A' }}</p>
                        <p style="color: #6b7280; font-size: 12px; margin: 4px 0 0 0;">{{ $order->customer?->phone ?? 'N/A' }}</p>
                    </div>

                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                        <p style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">Data do Pedido</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <p style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">Método de Pagamento</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">
                            @switch($order->payment_method)
                                @case('credit_card')
                                    <i class="fas fa-credit-card"></i> Cartão de Crédito
                                @break
                                @case('debit_card')
                                    <i class="fas fa-credit-card"></i> Cartão de Débito
                                @break
                                @case('pix')
                                    PIX
                                @break
                                @case('boleto')
                                    Boleto
                                @break
                                @case('paypal')
                                    PayPal
                                @break
                            @endswitch
                        </p>
                    </div>
                </div>

                <!-- Resumo Financeiro -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Resumo Financeiro</h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                        <span style="color: #6b7280;">Subtotal:</span>
                        <span style="color: #1f2937; font-weight: 600;">R$ {{ number_format($order->subtotal ?? ($order->total - 15), 2, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                        <span style="color: #6b7280;">Frete:</span>
                        <span style="color: #1f2937; font-weight: 600;">R$ 15,00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700;">
                        <span style="color: #1f2937;">Total:</span>
                        <span style="color: #3b82f6;">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Atualizar Status -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">Atualizar Status</h3>
                    
                    @if(empty($allowedStatuses))
                    <div style="padding: 12px; background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 4px; color: #92400e; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i>
                        <span>Este pedido está em um estado terminal ({{ ucfirst($order->status) }}) e não pode ser alterado.</span>
                    </div>
                    @else
                    <form method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                        @csrf
                        <div style="display: flex; gap: 8px;">
                            <button type="submit" formaction="{{ route('admin.orders.updateStatus', [$order->id, 'processing']) }}" 
                                style="flex: 1; padding: 8px; background-color: {{ in_array('processing', $allowedStatuses) ? '#dbeafe' : '#f3f4f6' }}; color: {{ in_array('processing', $allowedStatuses) ? '#1e40af' : '#9ca3af' }}; border: none; border-radius: 4px; font-weight: 600; cursor: {{ in_array('processing', $allowedStatuses) ? 'pointer' : 'not-allowed' }}; font-size: 12px;" 
                                {{ in_array('processing', $allowedStatuses) ? '' : 'disabled' }}
                                title="{{ in_array('processing', $allowedStatuses) ? 'Marcar como processando' : 'Transição não permitida a partir de ' . ucfirst($order->status) }}">
                                Processar
                            </button>
                            <button type="submit" formaction="{{ route('admin.orders.updateStatus', [$order->id, 'shipped']) }}" 
                                style="flex: 1; padding: 8px; background-color: {{ in_array('shipped', $allowedStatuses) ? '#e0e7ff' : '#f3f4f6' }}; color: {{ in_array('shipped', $allowedStatuses) ? '#3730a3' : '#9ca3af' }}; border: none; border-radius: 4px; font-weight: 600; cursor: {{ in_array('shipped', $allowedStatuses) ? 'pointer' : 'not-allowed' }}; font-size: 12px;"
                                {{ in_array('shipped', $allowedStatuses) ? '' : 'disabled' }}
                                title="{{ in_array('shipped', $allowedStatuses) ? 'Marcar como enviado' : 'Transição não permitida a partir de ' . ucfirst($order->status) }}">
                                Enviar
                            </button>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button type="submit" formaction="{{ route('admin.orders.updateStatus', [$order->id, 'delivered']) }}" 
                                style="flex: 1; padding: 8px; background-color: {{ in_array('delivered', $allowedStatuses) ? '#d1fae5' : '#f3f4f6' }}; color: {{ in_array('delivered', $allowedStatuses) ? '#065f46' : '#9ca3af' }}; border: none; border-radius: 4px; font-weight: 600; cursor: {{ in_array('delivered', $allowedStatuses) ? 'pointer' : 'not-allowed' }}; font-size: 12px;"
                                {{ in_array('delivered', $allowedStatuses) ? '' : 'disabled' }}
                                title="{{ in_array('delivered', $allowedStatuses) ? 'Marcar como entregue' : 'Transição não permitida a partir de ' . ucfirst($order->status) }}">
                                Entregar
                            </button>
                            <button type="submit" formaction="{{ route('admin.orders.updateStatus', [$order->id, 'cancelled']) }}" 
                                style="flex: 1; padding: 8px; background-color: {{ in_array('cancelled', $allowedStatuses) ? '#fee2e2' : '#f3f4f6' }}; color: {{ in_array('cancelled', $allowedStatuses) ? '#7f1d1d' : '#9ca3af' }}; border: none; border-radius: 4px; font-weight: 600; cursor: {{ in_array('cancelled', $allowedStatuses) ? 'pointer' : 'not-allowed' }}; font-size: 12px;"
                                {{ in_array('cancelled', $allowedStatuses) ? '' : 'disabled' }}
                                title="{{ in_array('cancelled', $allowedStatuses) ? 'Cancelar pedido' : 'Transição não permitida a partir de ' . ucfirst($order->status) }}">
                                Cancelar
                            </button>
                        </div>
                    </form>
                    @endif
                </div>

                <!-- WhatsApp Button -->
                @if($order->customer && $order->customer->phone)
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
                    $message = "Olá! Informação sobre o pedido " . $order->order_number . ". Status: " . translateOrderStatus($order->status) . ". Total: R$ " . number_format($order->total, 2, ',', '.');
                    $whatsappUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                ?>
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">Contato</h3>
                    <a href="{{ $whatsappUrl }}" target="_blank" style="display: block; width: 100%; padding: 12px; background-color: #25d366; color: white; border-radius: 4px; text-decoration: none; font-weight: 600; cursor: pointer; font-size: 14px; text-align: center; transition: background-color 0.3s;">
                        <i class="fab fa-whatsapp"></i> Enviar mensagem via WhatsApp
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para sugerir WhatsApp -->
<div id="whatsappModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; max-width: 500px; margin: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <h3 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 16px; text-align: center;">
            <i class="fas fa-paper-plane" style="color: #3b82f6; margin-right: 8px;"></i>
            Notificar cliente?
        </h3>
        
        <p style="color: #6b7280; margin-bottom: 20px; text-align: center;">
            O status do pedido foi alterado. Deseja enviar uma mensagem no WhatsApp para o cliente informando sobre a atualização?
        </p>
        
        <div style="background-color: #f3f4f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <strong style="color: #374151;">Mensagem que será enviada:</strong>
            <p id="whatsappMessage" style="margin: 8px 0 0 0; color: #1f2937; font-style: italic;"></p>
        </div>
        
        <div style="display: flex; gap: 12px; justify-content: center;">
            <button id="cancelWhatsApp" style="padding: 10px 20px; background-color: #6b7280; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                Não, apenas alterar status
            </button>
            <button id="sendWhatsApp" style="padding: 10px 20px; background-color: #25d366; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                <i class="fab fa-whatsapp"></i> Sim, enviar WhatsApp
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusForms = document.querySelectorAll('form[method="POST"]');
    const modal = document.getElementById('whatsappModal');
    const cancelBtn = document.getElementById('cancelWhatsApp');
    const sendBtn = document.getElementById('sendWhatsApp');
    const messageDiv = document.getElementById('whatsappMessage');
    
    let pendingForm = null;
    let pendingAction = null;
    
    // Interceptar cliques nos botões de status
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Verificar se é um botão de status sendo clicado
            const submitter = e.submitter;
            if (submitter && submitter.hasAttribute('formaction')) {
                e.preventDefault();
                
                // Extrair o status do formaction
                const action = submitter.getAttribute('formaction');
                const statusMatch = action.match(/\/status\/(\w+)$/);
                if (statusMatch) {
                    const newStatus = statusMatch[1];
                    
                    // Verificar se o cliente tem WhatsApp
                    @if($order->customer && $order->customer->phone)
                        showWhatsAppModal(this, submitter, newStatus);
                    @else
                        // Se não tem WhatsApp, submeter diretamente
                        this.submit();
                    @endif
                } else {
                    // Não é um botão de status, submeter normalmente
                    this.submit();
                }
            }
        });
    });
    
    function showWhatsAppModal(form, button, status) {
        pendingForm = form;
        pendingAction = button.getAttribute('formaction');
        
        // Traduzir status para português
        const statusTranslations = {
            'processing': 'Em processamento',
            'shipped': 'Enviado',
            'delivered': 'Entregue',
            'cancelled': 'Cancelado'
        };
        
        const statusText = statusTranslations[status] || status;
        const message = `Olá! Seu pedido {{ $order->order_number }} foi atualizado.\n\nStatus: ${statusText}\nTotal: R$ {{ number_format($order->total, 2, ',', '.') }}\n\nQualquer dúvida, entre em contato conosco!`;
        
        messageDiv.textContent = message;
        modal.style.display = 'flex';
    }
    
    // Cancelar e apenas alterar status
    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        if (pendingForm) {
            // Criar input hidden para o action e submeter
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = '_action';
            actionInput.value = pendingAction;
            pendingForm.appendChild(actionInput);
            pendingForm.action = pendingAction;
            pendingForm.submit();
        }
    });
    
    // Enviar WhatsApp e alterar status
    sendBtn.addEventListener('click', function() {
        @if($order->customer && $order->customer->phone)
            // Calcular novo status e montar mensagem
            const action = pendingAction;
            const statusMatch = action.match(/\/status\/(\w+)$/);
            if (statusMatch) {
                const newStatus = statusMatch[1];
                const statusTranslations = {
                    'processing': 'Em processamento',
                    'shipped': 'Enviado', 
                    'delivered': 'Entregue',
                    'cancelled': 'Cancelado'
                };
                
                const statusText = statusTranslations[newStatus] || newStatus;
                const phone = '{{ $phone }}';
                const message = `Olá! Seu pedido {{ $order->order_number }} foi atualizado.\n\nStatus: ${statusText}\nTotal: R$ {{ number_format($order->total, 2, ',', '.') }}\n\nQualquer dúvida, entre em contato conosco!`;
                const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                
                // Abrir WhatsApp
                window.open(whatsappUrl, '_blank');
            }
        @endif
        
        // Fechar modal e submeter formulário
        modal.style.display = 'none';
        if (pendingForm) {
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = '_action'; 
            actionInput.value = pendingAction;
            pendingForm.appendChild(actionInput);
            pendingForm.action = pendingAction;
            pendingForm.submit();
        }
    });
    
    // Fechar modal ao clicar fora
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>
@endsection
