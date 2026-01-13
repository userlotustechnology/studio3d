@extends('layouts.main')

@section('title', 'Livro Caixa')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Livro Caixa</h1>
            <p style="color: #6b7280;">Controle financeiro de entradas e saídas</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.cash-book.reports') }}" 
               style="background: white; color: #3b82f6; border: 1px solid #3b82f6; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-chart-bar"></i>
                Relatórios
            </a>
            <a href="{{ route('admin.cash-book.create') }}" 
               style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>
                Novo Lançamento
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #059669; color: #059669; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Resumo Financeiro -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div style="background: linear-gradient(135deg, #059669, #10b981); border-radius: 8px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600; margin-bottom: 8px;">Créditos</div>
                    <div style="color: white; font-size: 32px; font-weight: 700;">R$ {{ number_format($totalCredits, 2, ',', '.') }}</div>
                </div>
                <i class="fas fa-arrow-up" style="color: rgba(255,255,255,0.7); font-size: 32px;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #dc2626, #ef4444); border-radius: 8px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600; margin-bottom: 8px;">Débitos</div>
                    <div style="color: white; font-size: 32px; font-weight: 700;">R$ {{ number_format($totalDebits, 2, ',', '.') }}</div>
                </div>
                <i class="fas fa-arrow-down" style="color: rgba(255,255,255,0.7); font-size: 32px;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, {{ $netAmount >= 0 ? '#0284c7, #06b6d4' : '#f59e0b, #fbbf24' }}); border-radius: 8px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600; margin-bottom: 8px;">Saldo</div>
                    <div style="color: white; font-size: 32px; font-weight: 700;">R$ {{ number_format($netAmount, 2, ',', '.') }}</div>
                </div>
                <i class="fas fa-balance-scale" style="color: rgba(255,255,255,0.7); font-size: 32px;"></i>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('admin.cash-book.index') }}">
            <div style="display: grid; grid-template-columns: repeat(5, 1fr) auto; gap: 16px; align-items: end;">
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Tipo</label>
                    <select name="type" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">Todos</option>
                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Créditos</option>
                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Débitos</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Categoria</label>
                    <select name="category" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Forma Pagamento</label>
                    <select name="payment_method_id" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="">Todas</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}" {{ request('payment_method_id') == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Data Inicial</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Data Final</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" style="background: #059669; color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('admin.cash-book.index') }}" 
                       style="background: #f3f4f6; color: #6b7280; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Lançamentos -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Data</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Descrição</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Categoria</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Tipo</th>
                        <th style="text-align: right; padding: 16px; font-weight: 600; color: #1f2937;">Valor</th>
                        <th style="text-align: right; padding: 16px; font-weight: 600; color: #1f2937;">Taxa</th>
                        <th style="text-align: right; padding: 16px; font-weight: 600; color: #1f2937;">Líquido</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Pagamento</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $entry->transaction_date->format('d/m/Y') }}</div>
                            <div style="font-size: 12px; color: #9ca3af;">{{ $entry->transaction_date->format('H:i') }}</div>
                        </td>
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; color: #1f2937;">{{ Str::limit($entry->description, 40) }}</div>
                            @if($entry->order)
                                <div style="font-size: 12px; color: #3b82f6;">Pedido #{{ $entry->order->order_number }}</div>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="background: #f3f4f6; color: #1f2937; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                {{ ucfirst(str_replace('_', ' ', $entry->category)) }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($entry->type === 'credit')
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-arrow-up" style="font-size: 10px;"></i> CRÉDITO
                                </span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-arrow-down" style="font-size: 10px;"></i> DÉBITO
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <span style="font-size: 16px; font-weight: 700; color: {{ $entry->type === 'credit' ? '#059669' : '#dc2626' }};">
                                {{ $entry->type === 'credit' ? '+' : '-' }}R$ {{ number_format($entry->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            @if($entry->fee_amount > 0)
                                <span style="color: #f59e0b; font-weight: 600;">R$ {{ number_format($entry->fee_amount, 2, ',', '.') }}</span>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <span style="font-size: 16px; font-weight: 700; color: {{ $entry->net_amount >= 0 ? '#059669' : '#dc2626' }};">
                                {{ $entry->net_amount >= 0 ? '+' : '' }}R$ {{ number_format($entry->net_amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($entry->paymentMethod)
                                <div style="font-size: 12px; color: #6b7280;">{{ $entry->paymentMethod->name }}</div>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.cash-book.show', $entry) }}" 
                                   style="background: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Visualizar
                                </a>
                                <a href="{{ route('admin.cash-book.edit', $entry) }}" 
                                   style="background: #f3e8ff; color: #7c3aed; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Editar
                                </a>
                                <button onclick="confirmDelete('{{ route('admin.cash-book.destroy', $entry) }}', '{{ $entry->description }}')"
                                   style="background: #fee2e2; color: #dc2626; padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 48px; text-align: center; color: #6b7280;">
                            <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div>Nenhum lançamento encontrado</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($entries->hasPages())
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $entries->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o lançamento <strong id="deleteItemName"></strong>?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, description) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteItemName').textContent = description;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection