@extends('layouts.main')

@section('title', 'Formas de Pagamento')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Formas de Pagamento</h1>
            <p style="color: #6b7280;">Gerencie as formas de pagamento e suas taxas</p>
        </div>
        <a href="{{ route('admin.payment-methods.create') }}" 
           style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i>
            Nova Forma de Pagamento
        </a>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #059669; color: #059669; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #dc2626; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Nome</label>
                <input type="text" placeholder="Nome da forma de pagamento" 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Código</label>
                <input type="text" placeholder="Código" 
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Status</label>
                <select style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos</option>
                    <option value="active">Ativos</option>
                    <option value="inactive">Inativos</option>
                </select>
            </div>
            <button style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600;">
                Filtrar
            </button>
        </div>
    </div>

    <!-- Lista de Formas de Pagamento -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Nome</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Código</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Taxa %</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Taxa Fixa</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Compensação</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Status</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethods as $method)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $method->name }}</div>
                            @if($method->description)
                                <div style="font-size: 14px; color: #6b7280;">{{ Str::limit($method->description, 50) }}</div>
                            @endif
                        </td>
                        <td style="padding: 16px;">
                            <code style="background: #f3f4f6; color: #3b82f6; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $method->code }}</code>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($method->fee_percentage > 0)
                                <span style="color: #f59e0b; font-weight: 600;">{{ number_format($method->fee_percentage, 2, ',', '.') }}%</span>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($method->fee_fixed > 0)
                                <span style="color: #f59e0b; font-weight: 600;">R$ {{ number_format($method->fee_fixed, 2, ',', '.') }}</span>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($method->settlement_days > 0)
                                <span style="color: #3b82f6; font-weight: 600;">{{ $method->settlement_days }}d</span>
                            @else
                                <span style="color: #059669; font-weight: 600;">À vista</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($method->is_active)
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">ATIVO</span>
                            @else
                                <span style="background: #f3f4f6; color: #6b7280; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">INATIVO</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.payment-methods.show', $method) }}" 
                                   style="background: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Visualizar
                                </a>
                                <a href="{{ route('admin.payment-methods.edit', $method) }}" 
                                   style="background: #f3e8ff; color: #7c3aed; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Editar
                                </a>
                                <button onclick="confirmDelete('{{ route('admin.payment-methods.destroy', $method) }}', '{{ $method->name }}')"
                                   style="background: #fee2e2; color: #dc2626; padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 48px; text-align: center; color: #6b7280;">
                            <i class="fas fa-credit-card" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div>Nenhuma forma de pagamento encontrada</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($paymentMethods->hasPages())
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $paymentMethods->links() }}
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
                <p>Tem certeza que deseja excluir a forma de pagamento <strong id="deleteItemName"></strong>?</p>
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
function confirmDelete(url, name) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteItemName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection