@extends('layouts.main')

@section('title', 'Livro Caixa')

@section('content')
<style>
.cash-book-card {
    transition: all 0.3s ease;
}
.cash-book-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}
.action-btn {
    transition: all 0.2s ease;
}
.action-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
.table-row {
    transition: background-color 0.2s ease;
}
.table-row:hover {
    background-color: #f9fafb;
}
.filter-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>

<div style="padding: 24px; background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%); min-height: 100vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 8px; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-book" style="color: #3b82f6;"></i>
                Livro Caixa
            </h1>
            <p style="color: #64748b; font-size: 15px;">Controle financeiro completo de entradas e saídas</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.cash-book.reports') }}" 
               class="action-btn"
               style="background: white; color: #3b82f6; border: 2px solid #3b82f6; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.1);">
                <i class="fas fa-chart-line"></i>
                Relatórios
            </a>
            <a href="{{ route('admin.cash-book.create') }}" 
               class="action-btn"
               style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                <i class="fas fa-plus-circle"></i>
                Novo Lançamento
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-left: 4px solid #059669; color: #065f46; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15); display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="font-size: 24px; color: #059669;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Resumo Financeiro -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        <!-- Card Créditos -->
        <div class="cash-book-card" style="background: linear-gradient(135deg, #059669, #10b981); border-radius: 16px; padding: 28px; box-shadow: 0 8px 16px rgba(5, 150, 105, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 1;">
                <div>
                    <div style="color: rgba(255,255,255,0.95); font-size: 13px; font-weight: 700; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase;">
                        <i class="fas fa-arrow-trend-up" style="margin-right: 6px;"></i>Créditos
                    </div>
                    <div style="color: white; font-size: 36px; font-weight: 900; margin-bottom: 4px;">R$ {{ number_format($totalCredits, 2, ',', '.') }}</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px;">Entradas no período</div>
                </div>
                <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-up" style="color: white; font-size: 28px;"></i>
                </div>
            </div>
        </div>

        <!-- Card Débitos -->
        <div class="cash-book-card" style="background: linear-gradient(135deg, #dc2626, #ef4444); border-radius: 16px; padding: 28px; box-shadow: 0 8px 16px rgba(220, 38, 38, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 1;">
                <div>
                    <div style="color: rgba(255,255,255,0.95); font-size: 13px; font-weight: 700; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase;">
                        <i class="fas fa-arrow-trend-down" style="margin-right: 6px;"></i>Débitos
                    </div>
                    <div style="color: white; font-size: 36px; font-weight: 900; margin-bottom: 4px;">R$ {{ number_format($totalDebits, 2, ',', '.') }}</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px;">Saídas no período</div>
                </div>
                <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-down" style="color: white; font-size: 28px;"></i>
                </div>
            </div>
        </div>

        <!-- Card Saldo -->
        <div class="cash-book-card" style="background: linear-gradient(135deg, {{ $netAmount >= 0 ? '#0284c7, #0ea5e9' : '#f59e0b, #fbbf24' }}); border-radius: 16px; padding: 28px; box-shadow: 0 8px 16px rgba({{ $netAmount >= 0 ? '2, 132, 199' : '245, 158, 11' }}, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; justify-content: space-between; align-items: start; position: relative; z-index: 1;">
                <div>
                    <div style="color: rgba(255,255,255,0.95); font-size: 13px; font-weight: 700; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase;">
                        <i class="fas fa-wallet" style="margin-right: 6px;"></i>Saldo Líquido
                    </div>
                    <div style="color: white; font-size: 36px; font-weight: 900; margin-bottom: 4px;">R$ {{ number_format($netAmount, 2, ',', '.') }}</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px;">{{ $netAmount >= 0 ? 'Positivo' : 'Negativo' }}</div>
                </div>
                <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-balance-scale" style="color: white; font-size: 28px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div style="background: white; border-radius: 16px; padding: 28px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <i class="fas fa-filter" style="color: #3b82f6; font-size: 20px;"></i>
            <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0;">Filtros de Pesquisa</h2>
        </div>
        <form method="GET" action="{{ route('admin.cash-book.index') }}">
            <div style="display: grid; grid-template-columns: repeat(5, 1fr) auto; gap: 16px; align-items: end;">
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 13px;">
                        <i class="fas fa-exchange-alt" style="color: #6b7280; margin-right: 4px;"></i>Tipo
                    </label>
                    <select name="type" class="filter-input" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; background: white; transition: all 0.2s;">
                        <option value="">Todos</option>
                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>💚 Créditos</option>
                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>❤️ Débitos</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 13px;">
                        <i class="fas fa-tag" style="color: #6b7280; margin-right: 4px;"></i>Categoria
                    </label>
                    <select name="category" class="filter-input" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; background: white; transition: all 0.2s;">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ \App\Models\CashBook::translateCategory($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 13px;">
                        <i class="fas fa-credit-card" style="color: #6b7280; margin-right: 4px;"></i>Pagamento
                    </label>
                    <select name="payment_method_id" class="filter-input" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; background: white; transition: all 0.2s;">
                        <option value="">Todas</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}" {{ request('payment_method_id') == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 13px;">
                        <i class="fas fa-calendar-alt" style="color: #6b7280; margin-right: 4px;"></i>Data Inicial
                    </label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-input"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; transition: all 0.2s;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 13px;">
                        <i class="fas fa-calendar-check" style="color: #6b7280; margin-right: 4px;"></i>Data Final
                    </label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-input"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; transition: all 0.2s;">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="action-btn" style="background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('admin.cash-book.index') }}" class="action-btn"
                       style="background: #f3f4f6; color: #6b7280; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; border: 2px solid #e5e7eb;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Lançamentos -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; overflow: hidden;">
        <div style="padding: 24px; border-bottom: 2px solid #f3f4f6; background: linear-gradient(135deg, #f8fafc, #ffffff);">
            <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-list-ul" style="color: #3b82f6;"></i>
                Lançamentos Financeiros
            </h2>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                    <tr>
                        <th style="text-align: left; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-calendar" style="color: #64748b; margin-right: 6px;"></i>Data
                        </th>
                        <th style="text-align: left; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-file-alt" style="color: #64748b; margin-right: 6px;"></i>Descrição
                        </th>
                        <th style="text-align: center; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-tag" style="color: #64748b; margin-right: 6px;"></i>Categoria
                        </th>
                        <th style="text-align: center; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-exchange-alt" style="color: #64748b; margin-right: 6px;"></i>Tipo
                        </th>
                        <th style="text-align: right; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-dollar-sign" style="color: #64748b; margin-right: 6px;"></i>Valor
                        </th>
                        <th style="text-align: right; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-percentage" style="color: #64748b; margin-right: 6px;"></i>Taxa
                        </th>
                        <th style="text-align: right; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-wallet" style="color: #64748b; margin-right: 6px;"></i>Líquido
                        </th>
                        <th style="text-align: center; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-credit-card" style="color: #64748b; margin-right: 6px;"></i>Pagamento
                        </th>
                        <th style="text-align: center; padding: 18px 20px; font-weight: 700; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
                            <i class="fas fa-cog" style="color: #64748b; margin-right: 6px;"></i>Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr class="table-row" style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 18px 20px;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 14px;">{{ $entry->transaction_date->format('d/m/Y') }}</div>
                            <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">{{ $entry->transaction_date->format('H:i') }}</div>
                        </td>
                        <td style="padding: 18px 20px; max-width: 300px;">
                            <div style="font-weight: 600; color: #1e293b; font-size: 14px; margin-bottom: 4px;">{{ Str::limit($entry->description, 45) }}</div>
                            @if($entry->order)
                                <a href="{{ route('admin.orders.show', $entry->order) }}" style="font-size: 12px; color: #3b82f6; text-decoration: none; font-weight: 600;">
                                    <i class="fas fa-shopping-cart" style="font-size: 10px;"></i> Pedido #{{ $entry->order->order_number }}
                                </a>
                            @endif
                        </td>
                        <td style="padding: 18px 20px; text-align: center;">
                            <span style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0); color: #475569; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; border: 1px solid #cbd5e1;">
                                {{ \App\Models\CashBook::translateCategory($entry->category) }}
                            </span>
                        </td>
                        <td style="padding: 18px 20px; text-align: center;">
                            @if($entry->type === 'credit')
                                <span style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2); border: 1px solid #6ee7b7;">
                                    <i class="fas fa-arrow-up" style="font-size: 10px;"></i> CRÉDITO
                                </span>
                            @else
                                <span style="background: linear-gradient(135deg, #fecaca, #fca5a5); color: #7f1d1d; padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.2); border: 1px solid #f87171;">
                                    <i class="fas fa-arrow-down" style="font-size: 10px;"></i> DÉBITO
                                </span>
                            @endif
                        </td>
                        <td style="padding: 18px 20px; text-align: right;">
                            <span style="font-size: 16px; font-weight: 800; color: {{ $entry->type === 'credit' ? '#059669' : '#dc2626' }};">
                                {{ $entry->type === 'credit' ? '+' : '-' }}R$ {{ number_format($entry->amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 18px 20px; text-align: right;">
                            @if($entry->fee_amount > 0)
                                <span style="color: #f59e0b; font-weight: 700; font-size: 14px;">R$ {{ number_format($entry->fee_amount, 2, ',', '.') }}</span>
                            @else
                                <span style="color: #cbd5e1; font-weight: 600;">—</span>
                            @endif
                        </td>
                        <td style="padding: 18px 20px; text-align: right;">
                            <span style="font-size: 16px; font-weight: 800; color: {{ $entry->net_amount >= 0 ? '#059669' : '#dc2626' }};">
                                {{ $entry->net_amount >= 0 ? '+' : '' }}R$ {{ number_format($entry->net_amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 18px 20px; text-align: center;">
                            @if($entry->paymentMethod)
                                <span style="font-size: 13px; color: #64748b; font-weight: 600;">{{ $entry->paymentMethod->name }}</span>
                            @else
                                <span style="color: #cbd5e1; font-weight: 600;">—</span>
                            @endif
                        </td>
                        <td style="padding: 18px 20px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <a href="{{ route('admin.cash-book.show', $entry) }}" 
                                   class="action-btn"
                                   style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700; box-shadow: 0 2px 6px rgba(59, 130, 246, 0.15);">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.cash-book.edit', $entry) }}" 
                                   class="action-btn"
                                   style="background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #6b21a8; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700; box-shadow: 0 2px 6px rgba(124, 58, 237, 0.15);">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('{{ route('admin.cash-book.destroy', $entry) }}', '{{ $entry->description }}')"
                                   class="action-btn"
                                   style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; padding: 8px 14px; border: none; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.15);">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 64px; text-align: center;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-book-open" style="font-size: 36px; color: #94a3b8;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 18px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nenhum lançamento encontrado</div>
                                    <div style="font-size: 14px; color: #94a3b8;">Tente ajustar os filtros ou adicionar um novo lançamento</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($entries->hasPages())
        <div style="padding: 20px; border-top: 2px solid #f3f4f6; background: #fafbfc;">
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