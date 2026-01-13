@extends('layouts.main')

@section('title', 'Editar Forma de Pagamento')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-1">
                <i class="fas fa-credit-card me-2"></i>Editar Forma de Pagamento
            </h2>
            <p class="text-muted mb-0">{{ $paymentMethod->name }}</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $paymentMethod->name) }}" 
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
                                       value="{{ old('code', $paymentMethod->code) }}" 
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $paymentMethod->description) }}</textarea>
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
                                           value="{{ old('fee_percentage', $paymentMethod->fee_percentage) }}" 
                                           min="0" 
                                           max="100" 
                                           step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('fee_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fee_fixed" class="form-label">Taxa Fixa (R$) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" 
                                           class="form-control @error('fee_fixed') is-invalid @enderror" 
                                           id="fee_fixed" 
                                           name="fee_fixed" 
                                           value="{{ old('fee_fixed', $paymentMethod->fee_fixed) }}" 
                                           min="0" 
                                           step="0.01">
                                </div>
                                @error('fee_fixed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="settlement_days" class="form-label">Dias para Compensação *</label>
                                <input type="number" 
                                       class="form-control @error('settlement_days') is-invalid @enderror" 
                                       id="settlement_days" 
                                       name="settlement_days" 
                                       value="{{ old('settlement_days', $paymentMethod->settlement_days) }}" 
                                       min="0" 
                                       max="365">
                                @error('settlement_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Forma de pagamento ativa</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection