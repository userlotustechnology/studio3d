@extends('layouts.main')

@section('title', 'Histórico de Movimentações')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Histórico de Movimentações</h1>
            <p style="color: #6b7280;">Todas as entradas e saídas de estoque</p>
        </div>
        <a href="{{ route('admin.stock.index') }}" 
           style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar ao Estoque
        </a>
    </div>

    <!-- Filtros -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Produto</label>
                <input type="text" placeholder="Nome do produto" 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Tipo</label>
                <select style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos</option>
                    <option value="in">Entrada</option>
                    <option value="out">Saída</option>
                    <option value="adjustment">Ajuste</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Data inicial</label>
                <input type="date" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Data final</label>
                <input type="date" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <button style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600;">
                Filtrar
            </button>
        </div>
    </div>

    <!-- Lista de Movimentações -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Data</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Produto</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Tipo</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Quantidade</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Antes</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Depois</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Motivo</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Usuário</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px; color: #6b7280;">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 16px;">
                            <a href="{{ route('admin.stock.product-movements', $movement->product) }}" 
                               style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $movement->product->name }}</a>
                        </td>
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