@extends('layouts.main')

@section('title', 'Novo Usuário - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('admin.users.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Novo Usuário</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Preencha os dados para criar um novo usuário</p>
        </div>

        <!-- Form -->
        <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- Nome -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Nome *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Digite o nome completo">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Email *
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        placeholder="usuario@exemplo.com">
                    @error('email')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Senha e Confirmação -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <!-- Senha -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Senha *
                        </label>
                        <input type="password" name="password" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="Mínimo 8 caracteres">
                        @error('password')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmação de Senha -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Confirmar Senha *
                        </label>
                        <input type="password" name="password_confirmation" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="Repita a senha">
                    </div>
                </div>

                <!-- Função e Status -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 32px;">
                    <!-- Função -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Função *
                        </label>
                        <select name="role" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                            <option value="">Selecione uma função</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuário</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('role')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div style="display: flex; align-items: flex-end;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; width: 100%;">
                            <input type="checkbox" name="is_active" value="1" checked
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="color: #1f2937; font-weight: 600; font-size: 14px;">Usuário Ativo</span>
                        </label>
                    </div>
                </div>

                <!-- Botões -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; flex: 1;">
                        <i class="fas fa-save"></i> Criar Usuário
                    </button>
                    <a href="{{ route('admin.users.index') }}" style="background-color: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center; flex: 1;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
