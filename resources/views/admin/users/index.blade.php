@extends('layouts.main')

@section('title', 'Usuários - Admin')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Usuários</h1>
            <p style="color: #6b7280;">Gerencie os usuários do sistema</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i>
            Novo Usuário
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
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Usuário</label>
                <input type="text" name="search" placeholder="Nome ou Email" value="{{ request('search') }}"
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Função</label>
                <select name="role" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todas as funções</option>
                    <option value="admin" @if(request('role') === 'admin') selected @endif>Admin</option>
                    <option value="user" @if(request('role') === 'user') selected @endif>Usuário</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos</option>
                    <option value="active" @if(request('status') === 'active') selected @endif>Ativo</option>
                    <option value="inactive" @if(request('status') === 'inactive') selected @endif>Inativo</option>
                </select>
            </div>
            <button type="submit" style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Usuários Table -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Usuário</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Email</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Função</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Status</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Cadastro</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; color: #1f2937;">{{ $user->name }}</div>
                        </td>
                        <td style="padding: 16px; color: #6b7280;">{{ $user->email }}</td>
                        <td style="padding: 16px; text-align: center;">
                            @if($user->role === 'admin')
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">ADMIN</span>
                            @else
                                <span style="background: #dbeafe; color: #0c4a6e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">USUÁRIO</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($user->is_active)
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">ATIVO</span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">INATIVO</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center; color: #6b7280; font-size: 14px;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Editar
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border: none; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 48px; text-align: center; color: #6b7280;">
                            <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div>Nenhum usuário encontrado</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

