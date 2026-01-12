@extends('layouts.main')

@section('title', 'CRM - Clientes')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">CRM - Clientes</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerencie e visualize o histórico de seus clientes</p>
            </div>
            <a href="{{ route('admin.crm.customers.export') }}" style="background-color: #10b981; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i> Exportar CSV
            </a>
        </div>

        <!-- Filtro e Busca -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px; display: flex; gap: 12px; align-items: flex-end;">
            <div style="flex: 1;">
                <form method="GET" action="{{ route('admin.crm.customers.index') }}" style="display: flex; gap: 12px;">
                    <input type="text" name="search" placeholder="Buscar por nome, email ou CPF..." 
                        value="{{ request('search') }}" style="flex: 1; padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.crm.customers.index') }}" style="background-color: #6b7280; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    @endif
                </form>
            </div>
            <div style="background-color: #f0f9ff; padding: 12px 20px; border-radius: 6px; border-left: 4px solid #3b82f6;">
                <strong style="color: #1e40af;">Total de Clientes: {{ $customers->total() }}</strong>
            </div>
        </div>

        <!-- Tabela de Clientes -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Cliente</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Email</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">CPF</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Telefone</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Pedidos</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Total Gasto</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Ticket Médio</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Última Compra</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 16px;">
                                <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $customer->name }}</p>
                            </td>
                            <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                                <small>{{ $customer->email }}</small>
                            </td>
                            <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                                <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px;">{{ $customer->cpf }}</code>
                            </td>
                            <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                                {{ $customer->phone ?? '-' }}
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">{{ $customer->orders_count }}</span>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <strong style="color: #1f2937;">R$ {{ number_format($customer->total_spent, 2, ',', '.') }}</strong>
                            </td>
                            <td style="padding: 16px; text-align: right; color: #6b7280; font-size: 14px;">
                                R$ {{ number_format($customer->average_ticket, 2, ',', '.') }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 14px;">
                                @if($customer->orders->first())
                                    <small>{{ $customer->orders->first()->created_at->format('d/m/Y') }}</small>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <a href="{{ route('admin.crm.customers.show', $customer) }}" 
                                    style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Visualizar detalhes">
                                    <i class="fas fa-eye"></i> Detalhes
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center; color: #6b7280;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                                <p style="margin: 0;">Nenhum cliente encontrado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div style="display: flex; justify-content: center; margin-top: 30px;">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
