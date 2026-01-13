@extends('layouts.main')

@section('title', 'Nova Forma de Pagamento')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-1">
                <i class="fas fa-credit-card me-2"></i>Nova Forma de Pagamento
            </h2>
            <p class="text-muted mb-0">Cadastre uma nova forma de pagamento e suas taxas</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.payment-methods.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Ex: PIX, Cartão de Crédito"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Código *</label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       value="{{ old('code') }}" 
                                       placeholder="Ex: pix, credit_card"
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Código único sem espaços ou caracteres especiais</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Descrição opcional da forma de pagamento">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fee_percentage" class="form-label">Taxa Percentual (%) *</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('fee_percentage') is-invalid @enderror" 
                                           id="fee_percentage" 
                                           name="fee_percentage" 
                                           value="{{ old('fee_percentage', '0.00') }}" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('fee_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Ex: 3.5 para 3,5%</div>
                            </div>
                            
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