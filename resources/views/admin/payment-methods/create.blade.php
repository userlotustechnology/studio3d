@extends('layouts.main')

@section('title', 'Nova Forma de Pagamento')

@section('content')
<div style="padding: 24px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Nova Forma de Pagamento</h1>
            <p style="color: #6b7280;">Cadastre uma nova forma de pagamento e suas taxas</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('admin.payment-methods.store') }}" method="POST">
            @csrf
            
            <!-- Grid Layout para Nome e Código -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Nome *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Ex: PIX, Cartão de Crédito">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Código *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
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
                    placeholder="Descrição opcional da forma de pagamento">{{ old('description') }}</textarea>
                @error('description')
                <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Taxa Percentual e Taxa Fixa -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Taxa Percentual (%) *</label>
                    <input type="number" name="fee_percentage" value="{{ old('fee_percentage', '0.00') }}" 
                        min="0" max="100" step="0.01"
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="0.00">
                    @error('fee_percentage')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Ex: 3.5 para 3,5%</p>
                </div>
                
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Taxa Fixa (R$) *</label>
                    <input type="number" name="fee_fixed" value="{{ old('fee_fixed', '0.00') }}"
                        min="0" step="0.01"
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="0.00">
                    @error('fee_fixed')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                    <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Ex: 1.50 para R$ 1,50</p>
                </div>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 32px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked
                        style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                    <span style="color: #1f2937; font-weight: 600;">Ativo</span>
                </label>
            </div>

            <!-- Botões -->
            <div style="display: flex; justify-content: flex-start; gap: 12px;">
                <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; min-width: 150px;">
                    <i class="fas fa-save"></i> Criar Forma de Pagamento
                </button>
                <a href="{{ route('admin.payment-methods.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; min-width: 120px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
                            
                            <div class="col-md-6 mb-3">
                                <label for="fee_fixed" class="form-label">Taxa Fixa (R$) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" 
                                           class="form-control @error('fee_fixed') is-invalid @enderror" 
                                           id="fee_fixed" 
                                           name="fee_fixed" 
                                           value="{{ old('fee_fixed', '0.00') }}" 
                                           min="0" 
                                           step="0.01"
                                           placeholder="0.00">
                                </div>
                                @error('fee_fixed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Taxa fixa por transação</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="settlement_days" class="form-label">Dias para Compensação *</label>
                                <input type="number" 
                                       class="form-control @error('settlement_days') is-invalid @enderror" 
                                       id="settlement_days" 
                                       name="settlement_days" 
                                       value="{{ old('settlement_days', '0') }}" 
                                       min="0" 
                                       max="365"
                                       placeholder="0">
                                @error('settlement_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">0 = à vista, 1 = D+1, etc.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Forma de pagamento ativa</strong>
                                    </label>
                                    <div class="form-text">Apenas formas ativas aparecem no checkout</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Forma de Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-gerar código baseado no nome
document.getElementById('name').addEventListener('input', function() {
    const codeField = document.getElementById('code');
    if (!codeField.value) {
        const code = this.value
            .toLowerCase()
            .replace(/[^\w\s]/g, '')
            .replace(/\s+/g, '_');
        codeField.value = code;
    }
});
</script>
@endsection