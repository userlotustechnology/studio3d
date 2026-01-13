@extends('layouts.main')

@section('title', 'Ajustar Estoque - ' . $product->name)

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Ajustar Estoque</h1>
            <p style="color: #6b7280;">{{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.stock.product-movements', $product) }}" 
           style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <!-- Informações do Produto -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Informações do Produto</h2>
            
            <div style="space-y: 16px;">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 4px;">Nome</label>
                    <div style="color: #6b7280;">{{ $product->name }}</div>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 4px;">SKU</label>
                    <div style="color: #6b7280;">{{ $product->sku ?? 'N/A' }}</div>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 4px;">Preço</label>
                    <div style="color: #6b7280;">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                </div>
                
                <div style="background: #f9fafb; padding: 20px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 32px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">{{ $product->stock }}</div>
                    <div style="color: #6b7280; font-weight: 600;">Estoque Atual</div>
                </div>
            </div>
        </div>

        <!-- Formulário de Ajuste -->
        <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Ajustar Estoque</h2>
            
            <form method="POST" action="{{ route('admin.stock.process-adjustment', $product) }}">
                @csrf
                
                <div style="margin-bottom: 24px;">
                    <label for="new_stock" style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Novo Estoque</label>
                    <input type="number" id="new_stock" name="new_stock" value="{{ old('new_stock', $product->stock) }}" 
                           min="0" required
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px;"
                           onchange="calculateDifference()">
                    @error('new_stock')
                        <div style="color: #dc2626; margin-top: 4px; font-size: 14px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-bottom: 24px; padding: 16px; background: #f9fafb; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Estoque atual:</span>
                        <span style="font-weight: 600; color: #1f2937;">{{ $product->stock }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Novo estoque:</span>
                        <span style="font-weight: 600; color: #1f2937;" id="new-stock-display">{{ $product->stock }}</span>
                    </div>
                    <hr style="border: none; height: 1px; background: #e5e7eb; margin: 8px 0;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6b7280;">Diferença:</span>
                        <span style="font-weight: 600;" id="difference-display">0</span>
                    </div>
                </div>
                
                <div style="margin-bottom: 32px;">
                    <label for="reason" style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Motivo do Ajuste *</label>
                    <textarea id="reason" name="reason" required rows="4" 
                              style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"
                              placeholder="Descreva o motivo do ajuste de estoque...">{{ old('reason') }}</textarea>
                    @error('reason')
                        <div style="color: #dc2626; margin-top: 4px; font-size: 14px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" 
                            style="flex: 1; background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i>
                        Confirmar Ajuste
                    </button>
                    <a href="{{ route('admin.stock.product-movements', $product) }}" 
                       style="flex: 1; background: #f3f4f6; color: #6b7280; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateDifference() {
    const currentStock = {{ $product->stock }};
    const newStockInput = document.getElementById('new_stock');
    const newStock = parseInt(newStockInput.value) || 0;
    const difference = newStock - currentStock;
    
    document.getElementById('new-stock-display').textContent = newStock;
    
    const diffElement = document.getElementById('difference-display');
    if (difference > 0) {
        diffElement.textContent = '+' + difference;
        diffElement.style.color = '#059669';
    } else if (difference < 0) {
        diffElement.textContent = difference;
        diffElement.style.color = '#dc2626';
    } else {
        diffElement.textContent = '0';
        diffElement.style.color = '#6b7280';
    }
}
</script>
@endsection