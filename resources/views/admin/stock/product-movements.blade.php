@extends('layouts.main')

@section('title', 'Histórico - ' . $product->name)

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">{{ $product->name }}</h1>
            <div style="display: flex; gap: 24px; color: #6b7280; margin-bottom: 8px;">
                <span>SKU: {{ $product->sku ?? 'N/A' }}</span>
                <span>Estoque atual: <strong>{{ $product->stock }}</strong></span>
                <span>Preço: <strong>R$ {{ number_format($product->price, 2, ',', '.') }}</strong></span>
            </div>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.stock.adjust', $product) }}" 
               style="background: #7c3aed; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-edit"></i>
                Ajustar Estoque
            </a>
            <a href="{{ route('admin.stock.index') }}" 
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Resumo do Produto -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
            <div style="text-align: center; padding: 20px; background: #f9fafb; border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">{{ $product->stock }}</div>
                <div style="color: #6b7280; font-weight: 600;">Estoque Atual</div>
            </div>
            <div style="text-align: center; padding: 20px; background: #ecfdf5; border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #059669; margin-bottom: 8px;">{{ $movements->where('type', 'in')->sum('quantity') }}</div>
                <div style="color: #059669; font-weight: 600;">Total Entradas</div>
            </div>
            <div style="text-align: center; padding: 20px; background: #fef2f2; border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #dc2626; margin-bottom: 8px;">{{ abs($movements->where('type', 'out')->sum('quantity')) }}</div>
                <div style="color: #dc2626; font-weight: 600;">Total Saídas</div>
            </div>
            <div style="text-align: center; padding: 20px; background: #f3e8ff; border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #7c3aed; margin-bottom: 8px;">{{ $movements->count() }}</div>
                <div style="color: #7c3aed; font-weight: 600;">Movimentações</div>
            </div>
        </div>
    </div>

    <!-- Histórico de Movimentações -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
            <h2 style="font-size: 20px; font-weight: 700; color: #1f2937;">Histórico de Movimentações</h2>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Data</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Tipo</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Quantidade</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Antes</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Depois</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Motivo</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Usuário</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Pedido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px; color: #6b7280;">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            @if($movement->type === 'in')
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">ENTRADA</span>
                            @elseif($movement->type === 'out')
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">SAÍDA</span>
                            @else
                                <span style="background: #f3e8ff; color: #7c3aed; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">AJUSTE</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center; font-weight: 600; 
                            @if($movement->quantity > 0) color: #059669; @else color: #dc2626; @endif">
                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                        </td>
                        <td style="padding: 16px; text-align: center; color: #6b7280;">{{ $movement->stock_before }}</td>
                        <td style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937;">{{ $movement->stock_after }}</td>
                        <td style="padding: 16px; color: #6b7280;">{{ $movement->reason }}</td>
                        <td style="padding: 16px; color: #6b7280;">{{ $movement->user_name ?? '-' }}</td>
                        <td style="padding: 16px;">
                            @if($movement->order)
                                <a href="{{ route('admin.orders.show', $movement->order->uuid) }}" 
                                   style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                    #{{ $movement->order->order_number }}
                                </a>
                            @else
                                <span style="color: #6b7280;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 48px; text-align: center; color: #6b7280;">
                            <i class="fas fa-history" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div>Nenhuma movimentação encontrada</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($movements->hasPages())
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $movements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection