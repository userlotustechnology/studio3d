@extends('layouts.main')

@section('title', 'Novo Usuário - Admin')

@section('content')
<div style="padding: 24px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Novo Usuário</h1>
            <p style="color: #6b7280;">Preencha os dados para criar um novo usuário</p>
        </div>
        <a href="{{ route('admin.users.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <!-- Grid Layout para os campos -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- Nome -->
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Nome *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Digite o nome completo">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="usuario@exemplo.com">
                    @error('email')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Senha e Confirmação -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Senha *</label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Mínimo 8 caracteres">
                    @error('password')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Confirmar Senha *</label>
                    <input type="password" name="password_confirmation" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Repita a senha">
                </div>
            </div>

            <!-- Função e Status -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Função *</label>
                    <select name="role" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                        <option value="">Selecione uma função</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuário</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('role')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked
                            style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                        <span style="color: #1f2937; font-weight: 600;">Usuário Ativo</span>
                    </label>
                </div>
            </div>

            <!-- Botões -->
            <div style="display: flex; justify-content: flex-start; gap: 12px;">
                <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; min-width: 150px;">
                    <i class="fas fa-save"></i> Criar Usuário
                </button>
                <a href="{{ route('admin.users.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; min-width: 120px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
