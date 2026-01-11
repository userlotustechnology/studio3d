@extends('layouts.main')

@section('title', 'Fretes - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Fretes</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerenciamento de custos de frete por estado</p>
            </div>
            <a href="{{ route('admin.shipping-rates.create') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Novo Frete
            </a>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 6px; margin-bottom: 20px;">
            <p style="color: #065f46; margin: 0;">✓ {{ session('success') }}</p>
        </div>
        @endif

        <!-- Shipping Rates Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">UF</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Estado</th>
                        <th style="padding: 16px; text-align: right; color: #6b7280; font-weight: 600; font-size: 14px;">Frete</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Status</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippingRates as $rate)
                    <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                        <td style="padding: 16px; color: #1f2937; font-weight: 600; font-size: 14px;">{{ $rate->state_code }}</td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">{{ $rate->state_name }}</td>
                        <td style="padding: 16px; text-align: right; color: #1f2937; font-weight: 600; font-size: 14px;">R$ {{ number_format($rate->rate, 2, ',', '.') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="background-color: {{ $rate->is_active ? '#d1fae5' : '#fee2e2' }}; color: {{ $rate->is_active ? '#065f46' : '#7f1d1d' }}; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                {{ $rate->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.shipping-rates.edit', $rate) }}" title="Editar" style="background-color: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.2s;">
                                    <i class="fas fa-pencil-alt"></i> Editar
                                </a>

                                <!-- Toggle Active Button -->
                                <form action="{{ route('admin.shipping-rates.toggle-active', $rate) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $rate->is_active ? 'Desativar' : 'Ativar' }}" style="background-color: {{ $rate->is_active ? '#10b981' : '#f59e0b' }}; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.2s;">
                                        <i class="fas fa-toggle-{{ $rate->is_active ? 'on' : 'off' }}"></i> {{ $rate->is_active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.shipping-rates.destroy', $rate) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Tem certeza que deseja deletar este frete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Deletar" style="background-color: #ef4444; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.2s;">
                                        <i class="fas fa-trash-alt"></i> Deletar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 14px;">
                            <i class="fas fa-inbox" style="margin-right: 8px;"></i>Nenhum frete cadastrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
