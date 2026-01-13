@extends('layouts.main')

@section('title', 'Detalhes da Forma de Pagamento')

@section('content')
<style>
.stat-card {
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}
.action-btn {
    transition: all 0.2s ease;
}
.action-btn:hover {
    transform: scale(1.05);
}
</style>

<div style="padding: 24px; background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%); min-height: 100vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 8px; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-credit-card" style="color: #3b82f6;"></i>
                {{ $paymentMethod->name }}
            </h1>
            <p style="color: #64748b; font-size: 15px;">Informações completas da forma de pagamento</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.payment-methods.index') }}" 
               class="action-btn"
               style="background: white; color: #6b7280; border: 2px solid #d1d5db; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
            <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" 
               class="action-btn"
               style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                <i class="fas fa-edit"></i>
                Editar
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div style="margin-bottom: 32px;">
        @if($paymentMethod->is_active)
            <span style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2); border: 2px solid #6ee7b7;">
                <i class="fas fa-check-circle" style="font-size: 16px;"></i>
                ATIVO
            </span>
        @else
            <span style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #7f1d1d; padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2); border: 2px solid #f87171;">
                <i class="fas fa-times-circle" style="font-size: 16px;"></i>
                INATIVO
            </span>
        @endif
    </div>

    <!-- Estatísticas -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 16px; padding: 28px; box-shadow: 0 8px 16px rgba(59, 130, 246, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 1;">
                <div style="color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 700; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase;">
                    <i class="fas fa-shopping-cart" style="margin-right: 6px;"></i>Total de Pedidos
                </div>
                <div style="color: white; font-size: 48px; font-weight: 900;">{{ $paymentMethod->orders_count ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 16px; padding: 28px; box-shadow: 0 8px 16px rgba(16, 185, 129, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 1;">
                <div style="color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 700; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase;">
                    <i class="fas fa-book" style="margin-right: 6px;"></i>Lançamentos
                </div>
                <div style="color: white; font-size: 48px; font-weight: 900;">{{ $paymentMethod->cash_book_entries_count ?? 0 }}</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 16px; padding: 28px; box-shadow: 0 8px 16px rgba(245, 158, 11, 0.25); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 1;">
                <div style="color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 700; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase;">
                    <i class="fas fa-calendar-days" style="margin-right: 6px;"></i>Dias p/ Compensação
                </div>
                <div style="color: white; font-size: 48px; font-weight: 900;">{{ $paymentMethod->settlement_days }}</div>
            </div>
        </div>
    </div>

    <!-- Informações Detalhadas -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px;">
        <div style="padding: 24px; border-bottom: 2px solid #f3f4f6; background: linear-gradient(135deg, #f8fafc, #ffffff);">
            <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                Informações Gerais
            </h2>
        </div>
        <div style="padding: 32px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                <div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Nome
                        </label>
                        <div style="color: #0f172a; font-size: 18px; font-weight: 700;">
                            {{ $paymentMethod->name }}
                        </div>
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Código
                        </label>
                        <div style="background: #f1f5f9; color: #475569; padding: 10px 16px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 16px; font-weight: 600;">
                            {{ $paymentMethod->code }}
                        </div>
                    </div>
                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Status
                        </label>
                        <div>
                            @if($paymentMethod->is_active)
                                <span style="background: #d1fae5; color: #065f46; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 700;">
                                    <i class="fas fa-check-circle"></i> Ativo
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #7f1d1d; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 700;">
                                    <i class="fas fa-times-circle"></i> Inativo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    @if($paymentMethod->description)
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Descrição
                        </label>
                        <div style="background: #f9fafb; padding: 16px; border-radius: 8px; color: #475569; line-height: 1.6; border-left: 4px solid #3b82f6;">
                            {{ $paymentMethod->description }}
                        </div>
                    </div>
                    @endif
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Dias para Compensação
                        </label>
                        <div style="color: #0f172a; font-size: 18px; font-weight: 700;">
                            {{ $paymentMethod->settlement_days }} {{ $paymentMethod->settlement_days == 1 ? 'dia' : 'dias' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Taxas -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px;">
        <div style="padding: 24px; border-bottom: 2px solid #f3f4f6; background: linear-gradient(135deg, #f8fafc, #ffffff);">
            <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-percentage" style="color: #f59e0b;"></i>
                Taxas Aplicadas
            </h2>
        </div>
        <div style="padding: 32px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 12px; padding: 24px; border-left: 4px solid #f59e0b;">
                    <div style="color: #92400e; font-size: 13px; font-weight: 700; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-percentage" style="margin-right: 6px;"></i>Taxa Percentual
                    </div>
                    <div style="color: #78350f; font-size: 36px; font-weight: 900; margin-bottom: 4px;">
                        {{ number_format($paymentMethod->fee_percentage, 2, ',', '.') }}%
                    </div>
                    <div style="color: #92400e; font-size: 12px;">Sobre o valor da transação</div>
                </div>
                <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-radius: 12px; padding: 24px; border-left: 4px solid #3b82f6;">
                    <div style="color: #1e40af; font-size: 13px; font-weight: 700; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-money-bill" style="margin-right: 6px;"></i>Taxa Fixa
                    </div>
                    <div style="color: #1e3a8a; font-size: 36px; font-weight: 900; margin-bottom: 4px;">
                        R$ {{ number_format($paymentMethod->fee_fixed, 2, ',', '.') }}
                    </div>
                    <div style="color: #1e40af; font-size: 12px;">Valor fixo por transação</div>
                </div>
            </div>

            <!-- Exemplo de Cálculo -->
            <div style="background: #f9fafb; border-radius: 12px; padding: 20px; margin-top: 24px; border: 2px dashed #cbd5e1;">
                <div style="font-weight: 700; color: #475569; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-calculator" style="color: #3b82f6;"></i>
                    Exemplo de Cálculo (R$ 100,00)
                </div>
                @php
                    $exampleAmount = 100;
                    $percentageFee = ($exampleAmount * $paymentMethod->fee_percentage) / 100;
                    $totalFee = $percentageFee + $paymentMethod->fee_fixed;
                    $netAmount = $exampleAmount - $totalFee;
                @endphp
                <div style="display: flex; gap: 16px; font-size: 14px;">
                    <div>
                        <span style="color: #64748b;">Valor bruto:</span>
                        <span style="color: #0f172a; font-weight: 700; margin-left: 4px;">R$ {{ number_format($exampleAmount, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span style="color: #64748b;">Taxa %:</span>
                        <span style="color: #f59e0b; font-weight: 700; margin-left: 4px;">R$ {{ number_format($percentageFee, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span style="color: #64748b;">Taxa fixa:</span>
                        <span style="color: #f59e0b; font-weight: 700; margin-left: 4px;">R$ {{ number_format($paymentMethod->fee_fixed, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span style="color: #64748b;">Total taxas:</span>
                        <span style="color: #dc2626; font-weight: 700; margin-left: 4px;">R$ {{ number_format($totalFee, 2, ',', '.') }}</span>
                    </div>
                    <div style="border-left: 2px solid #cbd5e1; padding-left: 16px;">
                        <span style="color: #64748b;">Valor líquido:</span>
                        <span style="color: #059669; font-weight: 700; margin-left: 4px;">R$ {{ number_format($netAmount, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metadados e Auditoria -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; overflow: hidden;">
        <div style="padding: 24px; border-bottom: 2px solid #f3f4f6; background: linear-gradient(135deg, #f8fafc, #ffffff);">
            <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clock" style="color: #64748b;"></i>
                Informações de Auditoria
            </h2>
        </div>
        <div style="padding: 32px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-plus-circle" style="margin-right: 4px;"></i>Criado em
                    </label>
                    <div style="color: #0f172a; font-size: 16px; font-weight: 600;">
                        {{ $paymentMethod->created_at->format('d/m/Y H:i:s') }}
                    </div>
                    <div style="color: #94a3b8; font-size: 12px; margin-top: 4px;">
                        {{ $paymentMethod->created_at->diffForHumans() }}
                    </div>
                </div>
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-edit" style="margin-right: 4px;"></i>Última atualização
                    </label>
                    <div style="color: #0f172a; font-size: 16px; font-weight: 600;">
                        {{ $paymentMethod->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                    <div style="color: #94a3b8; font-size: 12px; margin-top: 4px;">
                        {{ $paymentMethod->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
