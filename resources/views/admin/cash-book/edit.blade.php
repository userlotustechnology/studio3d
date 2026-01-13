@extends('layouts.main')

@section('title', 'Editar Lançamento')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary mb-1">
                <i class="fas fa-edit me-2"></i>Editar Lançamento
            </h2>
            <p class="text-muted mb-0">Atualizar movimentação financeira</p>
        </div>
        <a href="{{ route('admin.cash-book.show', $cashBook) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.cash-book.update', $cashBook) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Tipo *</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">Selecione...</option>
                                    <option value="credit" {{ old('type', $cashBook->type) === 'credit' ? 'selected' : '' }}>Crédito (Entrada)</option>
                                    <option value="debit" {{ old('type', $cashBook->type) === 'debit' ? 'selected' : '' }}>Débito (Saída)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Categoria *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category" 
                                        required>
                                    <option value="">Selecione...</option>
                                    <option value="sale" {{ old('category', $cashBook->category) === 'sale' ? 'selected' : '' }}>Venda</option>
                                    <option value="payment_fee" {{ old('category', $cashBook->category) === 'payment_fee' ? 'selected' : '' }}>Taxa de Pagamento</option>
                                    <option value="shipping_revenue" {{ old('category', $cashBook->category) === 'shipping_revenue' ? 'selected' : '' }}>Frete Recebido</option>
                                    <option value="shipping_cost" {{ old('category', $cashBook->category) === 'shipping_cost' ? 'selected' : '' }}>Custo de Frete</option>
                                    <option value="product_cost" {{ old('category', $cashBook->category) === 'product_cost' ? 'selected' : '' }}>Custo de Produtos (CMV)</option>
                                    <option value="refund" {{ old('category', $cashBook->category) === 'refund' ? 'selected' : '' }}>Estorno</option>
                                    <option value="expense" {{ old('category', $cashBook->category) === 'expense' ? 'selected' : '' }}>Despesa</option>
                                    <option value="revenue" {{ old('category', $cashBook->category) === 'revenue' ? 'selected' : '' }}>Receita</option>
                                    <option value="withdrawal" {{ old('category', $cashBook->category) === 'withdrawal' ? 'selected' : '' }}>Retirada</option>
                                    <option value="investment" {{ old('category', $cashBook->category) === 'investment' ? 'selected' : '' }}>Investimento</option>
                                    <option value="other" {{ old('category', $cashBook->category) === 'other' ? 'selected' : '' }}>Outros</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição *</label>
                            <input type="text" 
                                   class="form-control @error('description') is-invalid @enderror" 
                                   id="description" 
                                   name="description" 
                                   value="{{ old('description', $cashBook->description) }}" 
                                   placeholder="Descrição do lançamento"
                                   required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Valor (R$) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount', $cashBook->amount) }}" 
                                           min="0" 
                                           step="0.01"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fee_amount" class="form-label">Taxa (R$)</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" 
                                           class="form-control @error('fee_amount') is-invalid @enderror" 
                                           id="fee_amount" 
                                           name="fee_amount" 
                                           value="{{ old('fee_amount', $cashBook->fee_amount ?? '0.00') }}" 
                                           min="0" 
                                           step="0.01"
                                           placeholder="0.00">
                                </div>
                                @error('fee_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="transaction_date" class="form-label">Data da Transação *</label>
                                <input type="date" 
                                       class="form-control @error('transaction_date') is-invalid @enderror" 
                                       id="transaction_date" 
                                       name="transaction_date" 
                                       value="{{ old('transaction_date', $cashBook->transaction_date->format('Y-m-d')) }}" 
                                       required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="settlement_date" class="form-label">Data de Compensação</label>
                                <input type="date" 
                                       class="form-control @error('settlement_date') is-invalid @enderror" 
                                       id="settlement_date" 
                                       name="settlement_date" 
                                       value="{{ old('settlement_date', $cashBook->settlement_date ? $cashBook->settlement_date->format('Y-m-d') : '') }}">
                                @error('settlement_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method_id" class="form-label">Forma de Pagamento</label>
                                <select class="form-select @error('payment_method_id') is-invalid @enderror" 
                                        id="payment_method_id" 
                                        name="payment_method_id">
                                    <option value="">Selecione...</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}" {{ old('payment_method_id', $cashBook->payment_method_id) == $method->id ? 'selected' : '' }}>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_method_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="order_id" class="form-label">Pedido Relacionado</label>
                                <select class="form-select @error('order_id') is-invalid @enderror" 
                                        id="order_id" 
                                        name="order_id">
                                    <option value="">Nenhum</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id', $cashBook->order_id) == $order->id ? 'selected' : '' }}>
                                            #{{ $order->order_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reference" class="form-label">Referência</label>
                            <input type="text" 
                                   class="form-control @error('reference') is-invalid @enderror" 
                                   id="reference" 
                                   name="reference" 
                                   value="{{ old('reference', $cashBook->reference) }}" 
                                   placeholder="ID de transação, número de documento, etc.">
                            @error('reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <button type="button" 
                                    class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>Excluir
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.cash-book.show', $cashBook) }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este lançamento?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.cash-book.destroy', $cashBook) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
