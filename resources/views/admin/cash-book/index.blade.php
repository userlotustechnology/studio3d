@extends('layouts.main')

@section('title', 'Livro Caixa')

@section('content')

<div style="background-color: #f3f4f6; padding: 30px 20px; min-height: 100vh;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Livro Caixa</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Controle financeiro completo de entradas e saídas</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('admin.cash-book.reports') }}" style="background-color: #f3f4f6; color: #6b7280; border: 1px solid #d1d5db; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chart-line"></i> Relatórios
                </a>
                <a href="{{ route('admin.cash-book.create') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-plus"></i> Novo Lançamento
                </a>
            </div>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 16px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 20px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Resumo Financeiro -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 30px;">
            <!-- Card Créditos -->
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;">Créditos</p>
                        <p style="color: #1f2937; font-size: 24px; font-weight: 700; margin: 0;">R$ {{ number_format($totalCredits, 2, ',', '.') }}</p>
                    </div>
                    <div style="background: #d1fae5; width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-up" style="color: #059669; font-size: 20px;"></i>
                    </div>
                </div>
            </div>

            <!-- Card Débitos -->
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #dc2626;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;">Débitos</p>
                        <p style="color: #1f2937; font-size: 24px; font-weight: 700; margin: 0;">R$ {{ number_format($totalDebits, 2, ',', '.') }}</p>
                    </div>
                    <div style="background: #fee2e2; width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-down" style="color: #dc2626; font-size: 20px;"></i>
                    </div>
                </div>
            </div>

            <!-- Card Saldo -->
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid {{ $netAmount >= 0 ? '#0284c7' : '#f59e0b' }};">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <p style="color: #6b7280; font-size: 13px; margin: 0 0 8px 0; font-weight: 600; text-transform: uppercase;">Saldo Líquido</p>
                        <p style="color: {{ $netAmount >= 0 ? '#0284c7' : '#f59e0b' }}; font-size: 24px; font-weight: 700; margin: 0;">R$ {{ number_format($netAmount, 2, ',', '.') }}</p>
                    </div>
                    <div style="background: {{ $netAmount >= 0 ? '#dbeafe' : '#fef3c7' }}; width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-wallet" style="color: {{ $netAmount >= 0 ? '#0284c7' : '#f59e0b' }}; font-size: 20px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtro e Busca -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px; display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <form method="GET" action="{{ route('admin.cash-book.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; flex: 1; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 13px;">Descrição</label>
                    <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" 
                           style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="min-width: 150px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 13px;">Tipo</label>
                    <select name="type" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; background: white;">
                        <option value="">Todos</option>
                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Créditos</option>
                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Débitos</option>
                    </select>
                </div>
                <div style="min-width: 150px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 13px;">Categoria</label>
                    <select name="category" style="width: 100%; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; background: white;">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ \App\Models\CashBook::translateCategory($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if(request('search') || request('type') || request('category'))
                    <a href="{{ route('admin.cash-book.index') }}" style="background-color: #6b7280; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                    @endif
                </div>
            </form>
            <div style="background-color: #f0f9ff; padding: 10px 16px; border-radius: 6px; border-left: 4px solid #3b82f6; font-weight: 600; color: #1e40af;">
                Total: {{ $entries->total() }}
            </div>
        </div>

        <!-- Tabela de Lançamentos -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Data</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Descrição</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Categoria</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Tipo</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Valor</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Taxa</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Líquido</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Pagamento</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                        <td style="padding: 16px;">
                            <span style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $entry->created_at->format('d/m/Y') }}</span>
                            <div style="font-size: 12px; color: #94a3b8;">{{ $entry->created_at->format('H:i') }}</div>
                        </td>
                        <td style="padding: 16px;">
                            @if($entry->order)
                                <a href="{{ route('admin.orders.show', $entry->order) }}" style="font-size: 12px; color: #3b82f6; text-decoration: none; font-weight: 600;">
                                    Pedido #{{ $entry->order->order_number }}
                                </a>
                            @endif
                        </td>
                        <td style="padding: 16px;">
                            <span style="background-color: #f3f4f6; color: #475569; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                {{ \App\Models\CashBook::translateCategory($entry->category) }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($entry->type === 'credit')
                                <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Crédito</span>
                            @else
                                <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">Débito</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <strong style="color: {{ $entry->type === 'credit' ? '#059669' : '#dc2626' }};font-size: 14px;">
                                {{ $entry->type === 'credit' ? '+' : '-' }}R$ {{ number_format($entry->amount, 2, ',', '.') }}
                            </strong>
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            @if($entry->fee_amount > 0)
                                <span style="color: #f59e0b; font-weight: 600;">R$ {{ number_format($entry->fee_amount, 2, ',', '.') }}</span>
                            @else
                                <span style="color: #cbd5e1;">—</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <strong style="color: {{ $entry->net_amount >= 0 ? '#059669' : '#dc2626' }}; font-size: 14px;">
                                {{ $entry->net_amount >= 0 ? '+' : '' }}R$ {{ number_format($entry->net_amount, 2, ',', '.') }}
                            </strong>
                        </td>
                        <td style="padding: 16px; text-align: center; font-size: 13px; color: #6b7280;">
                            {{ $entry->paymentMethod?->name ?? '—' }}
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.cash-book.show', $entry) }}" 
                                   style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Visualizar">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 40px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0; font-weight: 600;">Nenhum lançamento encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($entries->hasPages())
        <div style="display: flex; justify-content: center;">
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