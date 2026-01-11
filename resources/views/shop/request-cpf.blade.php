@extends('shop.layout')

@section('title', 'Informe seu CPF')

@section('content')
    <div class="container cpf-container" style="padding: 60px 20px; max-width: 500px; margin: 0 auto;">
        <div class="cpf-card" style="background: white; border-radius: 12px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <i class="fas fa-id-card" style="font-size: 48px; color: var(--primary-color); margin-bottom: 20px;"></i>
                <h1 style="font-size: 28px; color: var(--text-dark); margin-bottom: 10px;">Informe seu CPF</h1>
                <p style="color: var(--text-light); font-size: 14px;">
                    Para continuar sua compra, precisamos do seu CPF
                </p>
            </div>

            @if (session('error'))
                <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #7f1d1d;">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $productToAdd = session('product_to_add', []);
                $productId = $productToAdd['product_id'] ?? null;
                $quantity = $productToAdd['quantity'] ?? 1;
            @endphp

            @if($productId)
                <form method="POST" action="{{ route('cart.add', $productId) }}" id="cpfForm">
                    @csrf
                    <input type="hidden" name="quantity" value="{{ $quantity }}">
                    
                    <div style="margin-bottom: 25px;">
                        <label for="cpf" style="display: block; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">
                            CPF <span style="color: #ef4444;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="cpf" 
                            name="cpf" 
                            maxlength="14"
                            placeholder="000.000.000-00"
                            required
                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                            onfocus="this.style.borderColor='var(--primary-color)'"
                            onblur="this.style.borderColor='#e5e7eb'"
                        >
                        <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                            Informe apenas números ou use o formato 000.000.000-00
                        </small>
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
        const cpfInput = document.getElementById('cpf');
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
            });
        }

        // Validação básica antes de enviar
        if (cpfForm) {
            cpfForm.addEventListener('submit', function(e) {
                const cpf = cpfInput.value.replace(/\D/g, '');
                
                if (cpf.length !== 11) {
                    e.preventDefault();
                    alert('Por favor, informe um CPF válido com 11 dígitos.');
                    cpfInput.focus();
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