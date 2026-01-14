@extends('shop.layout')

@section('title', 'Informe seus dados')

@section('content')
    <div class="container cpf-container" style="padding: 60px 20px; max-width: 500px; margin: 0 auto;">
        <div class="cpf-card" style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <i class="fas fa-id-card" style="font-size: 48px; color: var(--primary-color); margin-bottom: 20px;"></i>
                <h1 style="font-size: 28px; color: var(--text-dark); margin-bottom: 10px;">Informe seus dados</h1>
                <p style="color: var(--text-light); font-size: 14px;">
                    Para continuar sua compra, precisamos de algumas informações
                </p>
            </div>

            @if (session('error'))
                <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #7f1d1d;">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $productToAdd = $productToAdd ?? [];
                $productUuid = $productToAdd['product_uuid'] ?? null;
                $quantity = $productToAdd['quantity'] ?? 1;
            @endphp

            @if($productUuid)
                <form method="POST" action="{{ route('cart.add', $productUuid) }}" id="cpfForm">
                    @csrf
                    <input type="hidden" name="quantity" value="{{ $quantity }}">
                    
                    <div style="margin-bottom: 20px;">
                        <label for="cpf" style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">
                            CPF <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="cpf" 
                            name="cpf" 
                            value="{{ old('cpf') }}"
                            maxlength="14"
                            placeholder="000.000.000-00"
                            required
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                            onfocus="this.style.borderColor='var(--primary-color)'"
                            onblur="this.style.borderColor='#e5e7eb'"
                        >
                        @error('cpf')
                        <small style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</small>
                        @else
                        <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                            Informe apenas números ou use o formato 000.000.000-00
                        </small>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">
                            Nome Completo <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            placeholder="Seu nome completo"
                            required
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                            onfocus="this.style.borderColor='var(--primary-color)'"
                            onblur="this.style.borderColor='#e5e7eb'"
                        >
                        @error('name')
                        <small style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</small>
                        @else
                        <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                            Informe seu nome completo como no documento
                        </small>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">
                            Email <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="seu@email.com"
                            required
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                            onfocus="this.style.borderColor='var(--primary-color)'"
                            onblur="this.style.borderColor='#e5e7eb'"
                        >
                        @error('email')
                        <small style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</small>
                        @else
                        <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                            Você receberá atualizações de pedidos neste email
                        </small>
                        @enderror
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label for="phone" style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">
                            Telefone <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            maxlength="15"
                            placeholder="(00) 00000-0000"
                            required
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                            onfocus="this.style.borderColor='var(--primary-color)'"
                            onblur="this.style.borderColor='#e5e7eb'"
                        >
                        @error('phone')
                        <small style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</small>
                        @else
                        <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                            Será usado para contato sobre sua compra
                        </small>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button 
                            type="submit" 
                            class="btn btn-primary" 
                            style="flex: 1; padding: 14px; font-size: 16px; font-weight: 600;"
                        >
                            Continuar
                        </button>
                        <a 
                            href="{{ route('shop.index') }}" 
                            style="flex: 1; padding: 14px; font-size: 16px; font-weight: 600; text-align: center; border: 2px solid #e5e7eb; background: white; color: var(--text-dark); border-radius: 6px; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
                        >
                            Cancelar
                        </a>
                    </div>
                </form>
            @else
                <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 20px; border-radius: 4px; color: #7f1d1d; text-align: center;">
                    <i class="fas fa-exclamation-circle" style="font-size: 32px; margin-bottom: 10px;"></i>
                    <p><strong>Erro!</strong> Nenhum produto para adicionar foi encontrado.</p>
                    <p style="margin-top: 10px;">
                        <a href="{{ route('shop.index') }}" style="color: #1f2937; text-decoration: underline; font-weight: 600;">Voltar para a loja</a>
                    </p>
                </div>
            @endif

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p style="color: var(--text-light); font-size: 13px; line-height: 1.6;">
                    <i class="fas fa-lock" style="color: #10b981; margin-right: 5px;"></i>
                    Seus dados estão seguros e protegidos
                </p>
            </div>
        </div>
    </div>

    <script>
        // Máscara de CPF
        const nameInput = document.getElementById('name');
        const cpfInput = document.getElementById('cpf');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const cpfForm = document.getElementById('cpfForm');
        
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }
                
                e.target.value = value;
                
                // Buscar dados do cliente se CPF tiver 11 dígitos
                if (value.replace(/\D/g, '').length === 11) {
                    fetchCustomerData(value);
                }
            });
        }

        // Máscara de telefone
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                
                e.target.value = value;
            });
        }

        // Buscar dados do cliente por CPF
        async function fetchCustomerData(cpf) {
            try {
                const cleanCpf = cpf.replace(/\D/g, '');
                const response = await fetch(`{{ route('cart.get-customer-by-cpf') }}?cpf=${cleanCpf}`);
                const data = await response.json();
                
                if (data.found) {
                    // Preencher automaticamente nome, email e telefone
                    if (data.name && nameInput && !nameInput.value) {
                        nameInput.value = data.name;
                    }
                    if (data.email && !emailInput.value.includes('@temp.local')) {
                        emailInput.value = data.email;
                    }
                    if (data.phone) {
                        // Formatar telefone
                        let phoneFormatted = data.phone.replace(/\D/g, '');
                        if (phoneFormatted.length >= 10) {
                            phoneFormatted = phoneFormatted.replace(/(\d{2})(\d)/, '($1) $2');
                            phoneFormatted = phoneFormatted.replace(/(\d{5})(\d)/, '$1-$2');
                        }
                        phoneInput.value = phoneFormatted;
                    }
                }
            } catch (error) {
                console.log('Erro ao buscar dados do cliente:', error);
            }
        }

        // Validação básica antes de enviar
        if (cpfForm) {
            cpfForm.addEventListener('submit', function(e) {
                const name = nameInput.value.trim();
                const cpf = cpfInput.value.replace(/\D/g, '');
                const email = emailInput.value.trim();
                const phone = phoneInput.value.replace(/\D/g, '');
                
                // Validar nome
                if (name.length < 3) {
                    e.preventDefault();
                    alert('Por favor, informe seu nome completo.');
                    nameInput.focus();
                    return false;
                }
                
                // Validar CPF
                if (cpf.length !== 11) {
                    e.preventDefault();
                    alert('Por favor, informe um CPF válido com 11 dígitos.');
                    cpfInput.focus();
                    return false;
                }

                // Validar email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Por favor, informe um email válido.');
                    emailInput.focus();
                    return false;
                }

                // Validar telefone (mínimo 10 dígitos)
                if (phone.length < 10) {
                    e.preventDefault();
                    alert('Por favor, informe um telefone válido com pelo menos 10 dígitos.');
                    phoneInput.focus();
                    return false;
                }
            });
        }
    </script>

    <style>
        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* CPF Page Responsive Styles */
        @media (max-width: 768px) {
            .cpf-container {
                padding: 40px 15px !important;
            }
            
            .cpf-card {
                padding: 25px !important;
            }
            
            .cpf-card h1 {
                font-size: 24px !important;
            }
            
            .cpf-card i.fa-id-card {
                font-size: 40px !important;
            }
        }
        
        @media (max-width: 480px) {
            .cpf-container {
                padding: 30px 12px !important;
            }
            
            .cpf-card {
                padding: 20px !important;
            }
            
            .cpf-card h1 {
                font-size: 22px !important;
            }
        }
    </style>
@endsection