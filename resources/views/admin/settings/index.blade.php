@extends('layouts.main')

@section('title', 'Configurações - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('dashboard') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Configurações do Sistema</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerencie as configurações da sua loja</p>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 6px; margin-bottom: 20px;">
            <p style="color: #065f46; margin: 0;">✓ {{ session('success') }}</p>
        </div>
        @endif

        <!-- Configurações -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <!-- Informações da Loja -->
            <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <i class="fas fa-store" style="font-size: 24px; color: #3b82f6;"></i>
                    <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0;">Informações da Loja</h2>
                </div>

                <form action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf

                    <!-- Nome da Loja -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Nome da Loja
                        </label>
                        <input type="text" name="store_name" value="{{ $settings['store_name'] }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="Nome da sua loja">
                        @error('store_name')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Email da Loja
                        </label>
                        <input type="email" name="store_email" value="{{ $settings['store_email'] }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="seu@email.com">
                        @error('store_email')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Telefone da Loja
                        </label>
                        <input type="text" name="store_phone" value="{{ $settings['store_phone'] }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="(11) 9999-9999">
                        @error('store_phone')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Endereço -->
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Endereço
                        </label>
                        <textarea name="store_address" rows="3"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box; font-family: inherit;"
                            placeholder="Endereço completo da sua loja">{{ $settings['store_address'] }}</textarea>
                        @error('store_address')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; width: 100%;">
                        <i class="fas fa-save"></i> Salvar Informações
                    </button>
                </form>
            </div>

            <!-- Configurações do Sistema -->
            <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <i class="fas fa-cogs" style="font-size: 24px; color: #10b981;"></i>
                    <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0;">Sistema</h2>
                </div>

                <form action="{{ route('admin.settings.system') }}" method="POST">
                    @csrf

                    <!-- Nome do Sistema -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Nome do Sistema
                        </label>
                        <input type="text" name="system_name" value="{{ $settings['system_name'] }}" readonly
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box; background-color: #f3f4f6; color: #6b7280;">
                    </div>

                    <!-- Versão -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Versão
                        </label>
                        <input type="text" name="system_version" value="{{ $settings['system_version'] }}" readonly
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box; background-color: #f3f4f6; color: #6b7280;">
                    </div>

                    <!-- Modo de Manutenção -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }}
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="color: #1f2937; font-weight: 600; font-size: 14px;">Modo de Manutenção</span>
                        </label>
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">O site será inacessível para visitantes</p>
                    </div>

                    <!-- Notificações por Email -->
                    <div style="margin-bottom: 24px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="email_notifications" value="1" {{ $settings['email_notifications'] ? 'checked' : '' }}
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <span style="color: #1f2937; font-weight: 600; font-size: 14px;">Notificações por Email</span>
                        </label>
                        <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">Receba alertas de novos pedidos e eventos</p>
                    </div>

                    <button type="submit" style="background-color: #10b981; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; width: 100%;">
                        <i class="fas fa-save"></i> Salvar Configurações
                    </button>
                </form>
            </div>
        </div>

        <!-- Estatísticas do Sistema -->
        <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <i class="fas fa-chart-bar" style="font-size: 24px; color: #f59e0b;"></i>
                <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0;">Estatísticas do Sistema</h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <!-- Card: Usuários -->
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 8px; padding: 24px; color: white; text-align: center;">
                    <i class="fas fa-users" style="font-size: 32px; margin-bottom: 12px; opacity: 0.8; display: block;"></i>
                    <h3 style="font-size: 32px; font-weight: 700; margin: 12px 0;">
                        {{ \App\Models\User::count() }}
                    </h3>
                    <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">Usuários Cadastrados</p>
                </div>

                <!-- Card: Produtos -->
                <div style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); border-radius: 8px; padding: 24px; color: white; text-align: center;">
                    <i class="fas fa-box" style="font-size: 32px; margin-bottom: 12px; opacity: 0.8; display: block;"></i>
                    <h3 style="font-size: 32px; font-weight: 700; margin: 12px 0;">
                        {{ \App\Models\Product::count() }}
                    </h3>
                    <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">Produtos Cadastrados</p>
                </div>

                <!-- Card: Pedidos -->
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 8px; padding: 24px; color: white; text-align: center;">
                    <i class="fas fa-shopping-cart" style="font-size: 32px; margin-bottom: 12px; opacity: 0.8; display: block;"></i>
                    <h3 style="font-size: 32px; font-weight: 700; margin: 12px 0;">
                        {{ \App\Models\Order::where('is_draft', false)->count() }}
                    </h3>
                    <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">Pedidos Completos</p>
                </div>

                <!-- Card: Categorias -->
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 8px; padding: 24px; color: white; text-align: center;">
                    <i class="fas fa-list" style="font-size: 32px; margin-bottom: 12px; opacity: 0.8; display: block;"></i>
                    <h3 style="font-size: 32px; font-weight: 700; margin: 12px 0;">
                        {{ \App\Models\Category::count() }}
                    </h3>
                    <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">Categorias</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

