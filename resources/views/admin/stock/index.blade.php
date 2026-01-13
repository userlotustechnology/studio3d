@extends('layouts.main')

@section('title', 'Controle de Estoque')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Controle de Estoque</h1>
            <p style="color: #6b7280;">Gerencie o estoque dos seus produtos</p>
        </div>
        <a href="{{ route('admin.stock.movements') }}" 
           style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-history"></i>
            Ver Movimentações
        </a>
    </div>

    <!-- Filtros -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Produto</label>
                <input type="text" placeholder="Nome ou SKU" 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Categoria</label>
                <select style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todas as categorias</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Status</label>
                <select style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos</option>
                    <option value="low">Estoque baixo</option>
                    <option value="out">Sem estoque</option>
                </select>
            </div>
            <button style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600;">
                Filtrar
            </button>
        </div>
    </div>

    <!-- Lista de Produtos -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Produto</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">SKU</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Categoria</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Estoque</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Status</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $product->name }}</div>
                            <div style="font-size: 14px; color: #6b7280;">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                        </td>
                        <td style="padding: 16px; color: #6b7280;">{{ $product->sku ?? '-' }}</td>
                        <td style="padding: 16px; color: #6b7280;">{{ $product->category->name ?? '-' }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="font-size: 18px; font-weight: 700; 
                                @if($product->stock <= 5) color: #dc2626; @elseif($product->stock <= 10) color: #f59e0b; @else color: #059669; @endif">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($product->stock == 0)
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">SEM ESTOQUE</span>
                            @elseif($product->stock <= 5)
                                <span style="background: #fef3c7; color: #f59e0b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">BAIXO</span>
                            @else
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">OK</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.stock.product-movements', $product) }}" 
                                   style="background: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Histórico
                                </a>
                                <a href="{{ route('admin.stock.adjust', $product) }}" 
                                   style="background: #f3e8ff; color: #7c3aed; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Ajustar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 48px; text-align: center; color: #6b7280;">
                            <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div>Nenhum produto encontrado</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection