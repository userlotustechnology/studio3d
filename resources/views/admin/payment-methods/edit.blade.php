@extends('layouts.main')

@section('title', 'Editar Forma de Pagamento')

@section('content')
<div style="padding: 24px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Editar Forma de Pagamento</h1>
            <p style="color: #6b7280;">{{ $paymentMethod->name }}</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Grid Layout para Nome e Código -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Nome *</label>
                    <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Código *</label>
                    <input type="text" name="code" value="{{ old('code', $paymentMethod->code) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Ex: credit-card">
                    @error('code')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Código único sem espaços ou caracteres especiais</p>
                </div>
            </div>

            <!-- Descrição -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Descrição</label>
                <textarea name="description" rows="3"
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box; font-family: inherit;"
                    placeholder="Descrição opcional da forma de pagamento">{{ old('description', $paymentMethod->description) }}</textarea>
                @error('description')
                <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Taxa Percentual e Taxa Fixa -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Taxa Percentual (%) *</label>
                    <input type="number" name="fee_percentage" value="{{ old('fee_percentage', $paymentMethod->fee_percentage) }}" 
                        min="0" max="100" step="0.01" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="0.00">
                    @error('fee_percentage')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Ex: 3.5 para 3,5%</p>
                </div>
                
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Taxa Fixa (R$) *</label>
                    <input type="number" name="fee_fixed" value="{{ old('fee_fixed', $paymentMethod->fee_fixed) }}"
                        min="0" step="0.01" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="0.00">
                    @error('fee_fixed')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Ex: 1.50 para R$ 1,50</p>
                </div>
            </div>

            <!-- Dias para Liquidação -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Dias para Liquidação *</label>
                <input type="number" name="settlement_days" value="{{ old('settlement_days', $paymentMethod->settlement_days) }}"
                    min="0" max="365" required
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                    placeholder="1">
                @error('settlement_days')
                <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                @enderror
                <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Quantos dias para o dinheiro cair na conta (ex: 1 para PIX, 30 para cartão)</p>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 32px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $paymentMethod->is_active ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                    <span style="color: #1f2937; font-weight: 600;">Ativo</span>
                </label>
            </div>

            <!-- Botões -->
            <div style="display: flex; justify-content: flex-start; gap: 12px;">
                <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; min-width: 150px;">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="{{ route('admin.payment-methods.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; min-width: 120px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection