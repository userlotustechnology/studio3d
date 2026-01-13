@extends('layouts.main')

@section('title', 'Detalhes do Lançamento')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Detalhes do Lançamento</h1>
            <p style="color: #6b7280;">Informações completas da movimentação financeira</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.cash-book.index') }}" 
               style="background: white; color: #6b7280; border: 1px solid #d1d5db; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
            <a href="{{ route('admin.cash-book.edit', $cashBook) }}" 
               style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit"></i>
                Editar
            </a>
        </div>
    </div>

    <!-- Card Principal -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <!-- Cabeçalho do Card -->
        <div style="background: {{ $cashBook->type === 'credit' ? 'linear-gradient(135deg, #059669, #10b981)' : 'linear-gradient(135deg, #dc2626, #ef4444)' }}; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                        {{ $cashBook->type === 'credit' ? 'CRÉDITO' : 'DÉBITO' }}
                    </div>
                    <div style="color: white; font-size: 36px; font-weight: 700;">
                        R$ {{ number_format($cashBook->amount, 2, ',', '.') }}
                    </div>
                </div>
                <i class="fas fa-{{ $cashBook->type === 'credit' ? 'arrow-up' : 'arrow-down' }}" 
                   style="color: rgba(255,255,255,0.7); font-size: 48px;"></i>
            </div>
        </div>

        <!-- Conteúdo do Card -->
        <div style="padding: 24px;">
            <!-- Informações Principais -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    Informações Principais
                </h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Categoria</div>
                        <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                            {{ \App\Models\CashBook::translateCategory($cashBook->category) }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Data</div>
                        <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                            {{ $cashBook->transaction_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Forma de Pagamento</div>
                        <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                            {{ $cashBook->paymentMethod ? $cashBook->paymentMethod->name : 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Data de Liquidação</div>
                        <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                            {{ $cashBook->settlement_date ? $cashBook->settlement_date->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valores Detalhados -->
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    Valores
                </h2>
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: #6b7280;">Valor Bruto:</span>
                        <span style="font-weight: 600; color: #1f2937;">R$ {{ number_format($cashBook->amount, 2, ',', '.') }}</span>
                    </div>
                    @if($cashBook->fee_amount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                        <span style="color: #6b7280;">Taxa:</span>
                        <span style="font-weight: 600; color: #dc2626;">- R$ {{ number_format($cashBook->fee_amount, 2, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-weight: 700; color: #1f2937;">Valor Líquido:</span>
                        <span style="font-weight: 700; font-size: 18px; color: {{ $cashBook->type === 'credit' ? '#059669' : '#dc2626' }};">
                            R$ {{ number_format($cashBook->net_amount, 2, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Descrição -->
            @if($cashBook->description)
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    Descrição
                </h2>
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    <p style="color: #4b5563; line-height: 1.6; margin: 0;">{{ $cashBook->description }}</p>
                </div>
            </div>
            @endif

            <!-- Pedido Relacionado -->
            @if($cashBook->order)
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    Pedido Relacionado
                </h2>
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Número do Pedido</div>
                            <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                                <a href="{{ route('admin.orders.show', $cashBook->order) }}" 
                                   style="color: #3b82f6; text-decoration: none;">
                                    #{{ $cashBook->order->order_number }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Cliente</div>
                            <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                                {{ $cashBook->order->customer ? $cashBook->order->customer->name : $cashBook->order->customer_name }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Status</div>
                            <div>
                                @php
                                    $statusColors = [
                                        'draft' => '#6b7280',
                                        'pending' => '#f59e0b',
                                        'processing' => '#3b82f6',
                                        'shipped' => '#8b5cf6',
                                        'delivered' => '#10b981',
                                        'cancelled' => '#ef4444',
                                        'refunded' => '#f97316'
                                    ];
                                @endphp
                                <span style="background: {{ $statusColors[$cashBook->order->status] ?? '#6b7280' }}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ \App\Models\OrderStatusHistory::translateStatus($cashBook->order->status) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div style="color: #6b7280; font-size: 14px; margin-bottom: 4px;">Total do Pedido</div>
                            <div style="color: #1f2937; font-size: 16px; font-weight: 600;">
                                R$ {{ number_format($cashBook->order->total, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Metadados -->
            @if($cashBook->metadata)
            <div style="margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    Informações Adicionais
                </h2>
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    @php
                        $metadata = is_array($cashBook->metadata) ? $cashBook->metadata : json_decode($cashBook->metadata, true);
                    @endphp
                    
                    @if(isset($metadata['products']) && is_array($metadata['products']))
                        <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 12px;">Produtos</h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #e5e7eb;">
                                    <th style="padding: 8px; text-align: left; font-size: 14px; color: #6b7280;">Produto</th>
                                    <th style="padding: 8px; text-align: center; font-size: 14px; color: #6b7280;">Qtd</th>
                                    <th style="padding: 8px; text-align: right; font-size: 14px; color: #6b7280;">Custo Unit.</th>
                                    <th style="padding: 8px; text-align: right; font-size: 14px; color: #6b7280;">Custo Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($metadata['products'] as $product)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 8px; color: #1f2937;">{{ $product['name'] }}</td>
                                    <td style="padding: 8px; text-align: center; color: #1f2937;">{{ $product['quantity'] }}</td>
                                    <td style="padding: 8px; text-align: right; color: #1f2937;">R$ {{ number_format($product['unit_cost'], 2, ',', '.') }}</td>
                                    <td style="padding: 8px; text-align: right; font-weight: 600; color: #1f2937;">R$ {{ number_format($product['total_cost'], 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <pre style="background: white; padding: 12px; border-radius: 6px; overflow-x: auto; color: #4b5563; font-size: 14px; margin: 0;">{{ json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    @endif
                </div>
            </div>
            @endif

            <!-- Informações de Auditoria -->
            <div style="background: #f9fafb; border-radius: 8px; padding: 16px; border-left: 4px solid #d1d5db;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                    <div>
                        <span style="color: #6b7280;">Criado em:</span>
                        <span style="color: #1f2937; font-weight: 600; margin-left: 8px;">
                            {{ $cashBook->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div>
                        <span style="color: #6b7280;">Atualizado em:</span>
                        <span style="color: #1f2937; font-weight: 600; margin-left: 8px;">
                            {{ $cashBook->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
