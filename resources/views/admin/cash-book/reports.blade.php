@extends('layouts.main')

@section('title', 'Relatórios - Livro Caixa')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Relatórios do Livro Caixa</h1>
            <p style="color: #6b7280;">Análise detalhada das movimentações financeiras</p>
        </div>
        <a href="{{ route('admin.cash-book.index') }}" 
           style="background: white; color: #3b82f6; border: 1px solid #3b82f6; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar ao Livro Caixa
        </a>
    </div>

    <!-- Filtros de Período -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('admin.cash-book.reports') }}">
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end;">
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Data Inicial</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Data Final</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Resumo por Categoria -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
            <i class="fas fa-tags" style="color: #3b82f6;"></i> Resumo por Categoria
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="text-align: left; padding: 12px; font-weight: 600; color: #1f2937;">Categoria</th>
                        <th style="text-align: left; padding: 12px; font-weight: 600; color: #1f2937;">Tipo</th>
                        <th style="text-align: center; padding: 12px; font-weight: 600; color: #1f2937;">Transações</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Total</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Média</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoryReport as $item)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px;">
                            <span style="font-weight: 600; color: #1f2937;">{{ \App\Models\CashBook::translateCategory($item->category) }}</span>
                        </td>
                        <td style="padding: 12px;">
                            @if($item->type === 'credit')
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">CRÉDITO</span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">DÉBITO</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center; color: #6b7280;">
                            {{ $item->total_transactions }}
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 700; color: {{ $item->type === 'credit' ? '#059669' : '#dc2626' }};">
                                R$ {{ number_format($item->total_amount, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right; color: #6b7280;">
                            R$ {{ number_format($item->avg_amount, 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: #9ca3af;">
                            Nenhuma movimentação no período
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Resumo por Forma de Pagamento -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
            <i class="fas fa-credit-card" style="color: #3b82f6;"></i> Resumo por Forma de Pagamento
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="text-align: left; padding: 12px; font-weight: 600; color: #1f2937;">Forma de Pagamento</th>
                        <th style="text-align: center; padding: 12px; font-weight: 600; color: #1f2937;">Transações</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Créditos</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Débitos</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Taxas</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Líquido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethodReport as $item)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px;">
                            <span style="font-weight: 600; color: #1f2937;">
                                {{ $item->paymentMethod->name ?? 'Não informado' }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center; color: #6b7280;">
                            {{ $item->total_transactions }}
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 600; color: #059669;">
                                R$ {{ number_format($item->total_credits, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 600; color: #dc2626;">
                                R$ {{ number_format($item->total_debits, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="color: #f59e0b; font-weight: 600;">
                                R$ {{ number_format($item->total_fees, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 700; color: {{ ($item->total_credits - $item->total_debits - $item->total_fees) >= 0 ? '#059669' : '#dc2626' }};">
                                R$ {{ number_format($item->total_credits - $item->total_debits - $item->total_fees, 2, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 32px; text-align: center; color: #9ca3af;">
                            Nenhuma movimentação no período
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Evolução Diária -->
    <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
            <i class="fas fa-chart-line" style="color: #3b82f6;"></i> Evolução Diária
        </h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="text-align: left; padding: 12px; font-weight: 600; color: #1f2937;">Data</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Créditos</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Débitos</th>
                        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Saldo do Dia</th>
                        <th style="text-align: center; padding: 12px; font-weight: 600; color: #1f2937; width: 200px;">Gráfico</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyEvolution as $day)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px;">
                            <span style="font-weight: 600; color: #1f2937;">
                                {{ \Carbon\Carbon::parse($day->transaction_date)->format('d/m/Y') }}
                            </span>
                            <div style="font-size: 12px; color: #9ca3af;">
                                {{ \Carbon\Carbon::parse($day->transaction_date)->translatedFormat('l') }}
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 600; color: #059669;">
                                R$ {{ number_format($day->daily_credits, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 600; color: #dc2626;">
                                R$ {{ number_format($day->daily_debits, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
                            <span style="font-weight: 700; color: {{ $day->daily_net >= 0 ? '#059669' : '#dc2626' }};">
                                {{ $day->daily_net >= 0 ? '+' : '' }}R$ {{ number_format($day->daily_net, 2, ',', '.') }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            @php
                                $maxValue = max($day->daily_credits, $day->daily_debits, 1);
                                $creditWidth = ($day->daily_credits / $maxValue) * 100;
                                $debitWidth = ($day->daily_debits / $maxValue) * 100;
                            @endphp
                            <div style="display: flex; gap: 4px; align-items: center;">
                                <div style="flex: 1; background: #f3f4f6; border-radius: 4px; height: 8px; overflow: hidden;">
                                    <div style="background: #059669; height: 100%; width: {{ $creditWidth }}%;"></div>
                                </div>
                                <div style="flex: 1; background: #f3f4f6; border-radius: 4px; height: 8px; overflow: hidden;">
                                    <div style="background: #dc2626; height: 100%; width: {{ $debitWidth }}%;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 32px; text-align: center; color: #9ca3af;">
                            Nenhuma movimentação no período
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
