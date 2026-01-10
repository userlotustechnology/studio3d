@extends('shop.layout')

@section('title', 'Consultar Pedido')

@section('content')
    <div class="container" style="padding: 60px 20px;">
        <div style="max-width: 500px; margin: 0 auto;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 32px; color: var(--text-dark); margin-bottom: 15px;">Consultar Pedido</h1>
                <p style="font-size: 16px; color: var(--text-light);">Digite seu CPF e o ID do pedido para rastrear sua compra</p>
            </div>

            <!-- Search Form -->
            <div style="background-color: white; border-radius: 8px; padding: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                @if($errors->any())
                    <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 25px; border-radius: 4px; color: #7f1d1d;">
                        <div style="font-weight: 700; margin-bottom: 10px;">
                            <i class="fas fa-exclamation-circle"></i> Erro na busca
                        </div>
                        @foreach($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session('error'))
                    <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 25px; border-radius: 4px; color: #7f1d1d;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('orders.search') }}">
                    @csrf

                    <!-- CPF Field -->
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">CPF *</label>
                        <input 
                            type="text" 
                            name="cpf" 
                            value="{{ old('cpf') }}" 
                            required 
                            placeholder="000.000.000-00"
                            style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit; font-size: 16px;"
                        >
                        @error('cpf')
                            <span style="color: #ef4444; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                        <div style="font-size: 13px; color: var(--text-light); margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Digite apenas números ou com pontuação
                        </div>
                    </div>

                    <!-- Order ID Field -->
                    <div style="margin-bottom: 30px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">ID do Pedido *</label>
                        <input 
                            type="text" 
                            name="order_id" 
                            value="{{ old('order_id') }}" 
                            required 
                            placeholder="PED-2026-000001"
                            style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit; font-size: 16px;"
                        >
                        @error('order_id')
                            <span style="color: #ef4444; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                        @enderror
                        <div style="font-size: 13px; color: var(--text-light); margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> Use o formato PED-YYYY-XXXXXX ou apenas o número do pedido
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-bottom: 20px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px; font-weight: 600;">
                            <i class="fas fa-search"></i> Buscar Pedido
                        </button>
                    </div>

                    <!-- Back Link -->
                    <div style="text-align: center;">
                        <a href="{{ route('shop.index') }}" style="color: var(--primary-color); text-decoration: none; font-size: 14px;">
                            <i class="fas fa-arrow-left"></i> Voltar à loja
                        </a>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div style="background-color: #eff6ff; border-left: 4px solid var(--primary-color); padding: 20px; border-radius: 4px; margin-top: 30px;">
                <h3 style="font-weight: 700; color: var(--primary-color); margin-bottom: 10px;">
                    <i class="fas fa-lightbulb"></i> Dúvidas?
                </h3>
                <ul style="margin-left: 20px; color: var(--text-light); font-size: 14px;">
                    <li style="margin-bottom: 8px;">O CPF deve ser o mesmo utilizado na compra</li>
                    <li style="margin-bottom: 8px;">O ID do pedido foi enviado por email após a confirmação</li>
                    <li>Se não encontrar seu pedido, verifique o email de spam</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Mascarar CPF em tempo real
        const cpfInput = document.querySelector('input[name="cpf"]');
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            if (value.length > 8) {
                value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6, 9) + '-' + value.slice(9, 11);
            } else if (value.length > 5) {
                value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6);
            } else if (value.length > 2) {
                value = value.slice(0, 3) + '.' + value.slice(3);
            }
            
            e.target.value = value;
        });
    </script>
@endsection
