@extends('layouts.main')

@section('title', 'Usuários - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Usuários</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerenciamento de usuários do sistema</p>
            </div>
            <a href="{{ route('admin.users.create') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Novo Usuário
            </a>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 6px; margin-bottom: 20px;">
            <p style="color: #065f46; margin: 0;">✓ {{ session('success') }}</p>
        </div>
        @endif

        <!-- Usuários Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">ID</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Nome</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Email</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Função</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Status</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Cadastro</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">#{{ $user->id }}</td>
                        <td style="padding: 16px;">
                            <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $user->name }}</p>
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">{{ $user->email }}</td>
                        <td style="padding: 16px;">
                            <span style="background-color: {{ $user->role === 'admin' ? '#fee2e2' : '#dbeafe' }}; color: {{ $user->role === 'admin' ? '#7f1d1d' : '#1e3a8a' }}; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($user->is_active)
                            <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Ativo</span>
                            @else
                            <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Inativo</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 14px;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.users.edit', $user->id) }}" style="background-color: #f59e0b; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Editar usuário">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: #ef4444; color: white; padding: 8px 16px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Deletar usuário">
                                        <i class="fas fa-trash"></i> Deletar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0;">Nenhum usuário cadastrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

