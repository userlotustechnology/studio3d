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
                    <input type="number" name="fee_fixed" value="{{ old('fee_fixed', $paymentMethod->fee_fixed) }}"
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
                
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Código *</label>
                    <input type="text" name="code" value="{{ old('code', $paymentMethod->code) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
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