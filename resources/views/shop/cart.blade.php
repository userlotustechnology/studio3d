@extends('shop.layout')

@section('title', 'Carrinho de Compras')

@section('content')
    <!-- Hero Section Moderna -->
    <div class="hero-modern">
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        <div class="container hero-content">
            <span class="hero-badge">
                🛒 Seu Carrinho
            </span>
            <h1>Carrinho de Compras</h1>
        </div>
    </div>

    <div class="container" style="padding: 60px 20px;">
        <div class="cart-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Cart Items -->
            <div class="cart-items-section">
                <h1 style="font-size: 32px; margin-bottom: 30px; color: var(--text-dark);"><i class="fas fa-shopping-cart"></i> Seu Carrinho</h1>

                @if (session('success'))
                    <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981; padding: 16px 20px; margin-bottom: 20px; border-radius: 12px; color: #065f46; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; padding: 16px 20px; margin-bottom: 20px; border-radius: 12px; color: #7f1d1d; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(count($items) > 0)
                    <div style="background-color: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                        @foreach($items as $item)
                            <div class="cart-item-modern" style="display: grid; grid-template-columns: 120px 1fr auto auto auto; gap: 30px; align-items: center; padding: 25px; border-bottom: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <!-- Product Image -->
                                <div class="cart-item-image-wrapper" style="position: relative; border-radius: 12px; overflow: hidden; background: #f9fafb;">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" style="width: 120px; height: 120px; object-fit: cover; transition: transform 0.3s ease;">
                                    @else
                                        <div style="width: 120px; height: 120px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-box" style="font-size: 36px; color: #9ca3af;"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="cart-item-info-modern">
                                    <h3 style="font-weight: 700; margin-bottom: 8px; color: var(--text-dark); font-size: 16px;">
                                        @if($item->product)
                                            <a href="{{ route('shop.show', $item->product->uuid) }}" style="color: #1f2937; text-decoration: none; transition: color 0.3s;">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h3>
                                    <p style="color: #667eea; font-weight: 600; font-size: 15px;">
                                        R$ {{ number_format($item->product_price, 2, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Quantity -->
                                <div class="cart-item-quantity-modern" style="display: flex; align-items: center; gap: 0; border: 2px solid #e5e7eb; border-radius: 10px;">
                                    <form method="POST" action="{{ route('cart.update', $item->product->uuid) }}" style="display: flex; gap: 0; align-items: center;" class="quantity-form">
                                        @csrf
                                        <button type="button" class="qty-btn-modern" onclick="decrementQuantity(this)" style="background: none; border: none; padding: 8px 12px; cursor: pointer; color: #667eea; font-weight: 600; font-size: 18px; transition: all 0.3s;">−</button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="qty-input-modern" style="width: 50px; text-align: center; padding: 8px; border: none; background: transparent; font-weight: 600; border-left: 2px solid #e5e7eb; border-right: 2px solid #e5e7eb;">
                                        <button type="button" class="qty-btn-modern" onclick="incrementQuantity(this)" style="background: none; border: none; padding: 8px 12px; cursor: pointer; color: #667eea; font-weight: 600; font-size: 18px; transition: all 0.3s;">+</button>
                                    </form>
                                </div>

                                <!-- Total -->
                                <div class="cart-item-total-modern" style="text-align: right;">
                                    <div style="font-size: 20px; font-weight: 800; color: #667eea;">
                                        R$ {{ number_format($item->product_price * $item->quantity, 2, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Remove -->
                                <div class="cart-item-remove" style="display: flex; justify-content: center;">
                                    <form method="POST" action="{{ route('cart.remove', $item->product->uuid) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: white; border: 2px solid #fee2e2; color: #ef4444; cursor: pointer; border-radius: 10px; padding: 10px 12px; transition: all 0.3s; font-size: 18px;" title="Remover produto">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 20px; text-align: right;">
                        <form method="POST" action="{{ route('cart.clear') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: 2px solid #e5e7eb; color: #ef4444; cursor: pointer; padding: 10px 20px; border-radius: 10px; font-weight: 600; transition: all 0.3s;">
                                <i class="fas fa-trash"></i> Limpar Carrinho
                            </button>
                        </form>
                    </div>
                @else
                    <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 80px 40px; text-align: center; border-radius: 16px; border: 2px dashed #e5e7eb;">
                        <i class="fas fa-shopping-cart" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px; display: block;"></i>
                        <h2 style="color: var(--text-dark); margin-bottom: 10px; font-size: 22px;">Carrinho vazio</h2>
                        <p style="color: var(--text-light); margin-bottom: 30px;">Você ainda não adicionou nenhum produto ao carrinho.</p>
                        <a href="{{ route('shop.index') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.3s; box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);">
                            <i class="fas fa-shopping-bag"></i> Continuar comprando
                        </a>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            @if(count($items) > 0)
                <div class="cart-summary-section">
                    <div class="cart-summary-modern" style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: sticky; top: 100px;">
                        <h2 style="font-size: 22px; margin-bottom: 30px; color: var(--text-dark); font-weight: 700;">Resumo do Pedido</h2>

                        <!-- CEP Input Section -->
                        <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #1f2937; font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #667eea;"></i>CEP para Cálculo de Frete
                            </label>
                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 8px; align-items: flex-end;">
                                <input type="text" id="cep-input" placeholder="Digite seu CEP" maxlength="9" style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;" value="{{ session('cep') ?? '' }}">
                                <button type="button" id="calc-shipping-btn" onclick="calculateShippingCost()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; white-space: nowrap;">
                                    <i class="fas fa-calculator"></i> Calcular
                                </button>
                            </div>
                            <div id="cep-error" style="display: none; color: #ef4444; font-size: 14px; margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span id="cep-error-text"></span>
                            </div>
                            <div id="cep-loading" style="display: none; color: #667eea; font-size: 14px; margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-spinner fa-spin"></i>
                                <span>Calculando frete...</span>
                            </div>
                        </div>

                        <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 20px; border-radius: 12px; margin-bottom: 30px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                <span style="color: #6b7280; font-weight: 500;">Subtotal</span>
                                <span style="font-weight: 700; color: #1f2937;">R$ <span id="subtotal-amount">{{ number_format($subtotal, 2, ',', '.') }}</span></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb;">
                                <span style="color: #6b7280; font-weight: 500;">Frete</span>
                                <span style="font-weight: 700; color: #667eea;">
                                    @if($shippingCost == 0)
                                        <span style="color: #10b981;">GRÁTIS 🎉</span>
                                    @else
                                        R$ <span id="shipping-amount">{{ number_format($shippingCost, 2, ',', '.') }}</span>
                                    @endif
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-top: 12px; padding-top: 12px;">
                                <span style="font-size: 18px; font-weight: 700; color: #1f2937;">Total</span>
                                <span style="font-size: 28px; font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    R$ <span id="total-amount">{{ number_format($total, 2, ',', '.') }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Indicador de Frete Grátis -->
                        @php
                            $freeShippingMinimum = \App\Models\Setting::get('free_shipping_minimum', 0);
                        @endphp
                        @if($freeShippingMinimum > 0)
                            <div id="free-shipping-indicator" style="margin-bottom: 20px;">
                                @if($subtotal >= $freeShippingMinimum)
                                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 16px; border-radius: 8px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fas fa-check-circle"></i>
                                        <span style="font-weight: 600;">🎉 Parabéns! Você ganhou frete grátis!</span>
                                    </div>
                                @else
                                    @php
                                        $remaining = $freeShippingMinimum - $subtotal;
                                    @endphp
                                    <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 12px 16px; border-radius: 8px; text-align: center;">
                                        <div style="margin-bottom: 4px; font-weight: 600;">🚚 Frete grátis</div>
                                        <div style="font-size: 14px;">Compre mais <strong>R$ {{ number_format($remaining, 2, ',', '.') }}</strong> e ganhe frete grátis!</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div style="display: grid; gap: 12px;">
                            <a href="{{ route('cart.checkout') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 20px; text-align: center; display: block; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);">
                                <i class="fas fa-lock"></i> Finalizar compra
                            </a>
                            <a href="{{ route('shop.index') }}" style="background: white; color: #667eea; padding: 14px 20px; text-align: center; display: block; border-radius: 12px; font-weight: 600; text-decoration: none; border: 2px solid #667eea; transition: all 0.3s;">
                                <i class="fas fa-arrow-left"></i> Continuar comprando
                            </a>
                        </div>

                        @php
                            $minimumOrderValue = \App\Models\Setting::get('minimum_order_value', 0);
                            $meetsMinimum = $minimumOrderValue <= 0 || $subtotal >= $minimumOrderValue;
                        @endphp

                        @if($minimumOrderValue > 0)
                        <div style="margin-top: 15px; padding: 15px; background-color: {{ $meetsMinimum ? '#d1fae5' : '#fee2e2' }}; border-radius: 10px; font-size: 13px; color: {{ $meetsMinimum ? '#065f46' : '#991b1b' }}; border: 1px solid {{ $meetsMinimum ? '#bbf7d0' : '#fecaca' }}; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-{{ $meetsMinimum ? 'check-circle' : 'exclamation-circle' }}"></i>
                            <div>
                                @if($meetsMinimum)
                                    Valor mínimo atendido ✓
                                @else
                                    Valor mínimo: R$ {{ number_format($minimumOrderValue, 2, ',', '.') }}
                                    <br>
                                    Faltam: R$ {{ number_format($minimumOrderValue - $subtotal, 2, ',', '.') }}
                                @endif
                            </div>
                        </div>
                        @endif

                        <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #e5e7eb; font-size: 13px; color: #6b7280; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-shield-alt" style="color: #10b981;"></i> Compra 100% segura
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Hero Section */
        .hero-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 20px 120px;
            position: relative;
            overflow: hidden;
        }

        .hero-bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: white;
            top: -100px;
            right: -100px;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: white;
            bottom: -50px;
            left: -50px;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: white;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .hero-content {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 10px 24px;
            border-radius: 50px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .hero-modern h1 {
            font-size: 48px;
            font-weight: 800;
            color: white;
            margin-bottom: 0;
            line-height: 1.2;
        }

        /* Cart Modern Styles */
        .cart-item-modern {
            border-radius: 12px;
            margin-bottom: 0;
        }

        .cart-item-modern:hover {
            background-color: #f9fafb;
        }

        .cart-item-image-wrapper {
            overflow: hidden;
            aspect-ratio: 1;
        }

        .cart-item-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-modern:hover .cart-item-image-wrapper img {
            transform: scale(1.08);
        }

        .cart-item-info-modern a {
            text-decoration: none;
            color: #1f2937;
            transition: color 0.3s;
        }

        .cart-item-info-modern a:hover {
            color: #667eea;
        }

        .qty-btn-modern:hover {
            background: #f3f4f6 !important;
        }

        .qty-input-modern {
            font-size: 16px !important;
        }

        .qty-input-modern:focus {
            outline: none;
        }

        /* Cart Summary Modern */
        .cart-summary-modern {
            border: 2px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .cart-summary-modern:hover {
            border-color: #e5e7eb;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-modern {
                padding: 80px 20px 100px;
            }

            .hero-modern h1 {
                font-size: 42px;
            }

            .cart-layout {
                gap: 30px !important;
            }
        }
        
        @media (max-width: 768px) {
            .hero-modern h1 {
                font-size: 32px;
            }

            .cart-layout {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            
            .cart-items-section h1 {
                font-size: 26px !important;
            }
            
            .cart-item-modern {
                grid-template-columns: 100px 1fr auto auto !important;
                gap: 20px !important;
                padding: 20px !important;
                align-items: flex-start !important;
            }
            
            .cart-item-image-wrapper {
                width: 100px;
                height: 100px;
                grid-row: 1 / 3;
            }

            .cart-item-info-modern {
                grid-column: 2;
                grid-row: 1;
            }
            
            .cart-item-quantity-modern {
                grid-column: 3;
                grid-row: 1 / 3;
                align-self: center;
            }
            
            .cart-item-total-modern {
                grid-column: 2;
                grid-row: 2;
                align-self: flex-end;
                margin-bottom: 8px;
            }

            .cart-item-remove {
                grid-column: 4;
                grid-row: 1 / 3;
                align-self: center;
            }
            
            .cart-summary-modern {
                position: static !important;
            }
            
            .cart-summary-modern h2 {
                font-size: 20px !important;
            }
        }
        
        @media (max-width: 480px) {
            .hero-modern {
                padding: 60px 20px 80px;
            }

            .hero-modern h1 {
                font-size: 24px;
            }

            .container {
                padding: 30px 15px !important;
            }
            
            .cart-items-section h1 {
                font-size: 20px !important;
                margin-bottom: 20px !important;
            }
            
            .cart-item-modern {
                grid-template-columns: 80px 1fr auto auto !important;
                gap: 15px !important;
                padding: 15px !important;
                align-items: flex-start !important;
            }
            
            .cart-item-image-wrapper {
                width: 80px;
                height: 80px;
                grid-row: 1 / 3;
            }

            .cart-item-info-modern {
                grid-column: 2 / -1;
                grid-row: 1;
            }

            .cart-item-info-modern h3 {
                font-size: 14px !important;
            }
            
            .cart-item-quantity-modern {
                grid-column: 3;
                grid-row: 2;
                align-self: center;
            }
            
            .qty-btn-modern {
                padding: 6px 10px !important;
            }
            
            .qty-input-modern {
                width: 45px !important;
                padding: 6px !important;
            }

            .cart-item-total-modern {
                grid-column: 2;
                grid-row: 2;
                align-self: center;
                font-size: 16px !important;
            }

            .cart-item-remove {
                grid-column: 4;
                grid-row: 2;
                align-self: center;
            }
            
            .cart-summary-modern {
                padding: 24px !important;
            }
        }
    </style>

    <script>
        // Variáveis globais
        const subtotalAmount = {{ $subtotal }};
        let currentShippingCost = {{ $shippingCost }};

        // Funções para incrementar/decrementar quantidade
        function incrementQuantity(btn) {
            const input = btn.parentElement.querySelector('.qty-input-modern');
            input.value = parseInt(input.value) + 1;
            submitForm(btn.parentElement);
        }

        function decrementQuantity(btn) {
            const input = btn.parentElement.querySelector('.qty-input-modern');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                submitForm(btn.parentElement);
            }
        }

        // Submeter formulário automaticamente ao mudar a quantidade
        function submitForm(form) {
            form.submit();
        }

        // Adicionar evento de mudança ao input de quantidade
        document.querySelectorAll('.qty-input-modern').forEach(input => {
            input.addEventListener('change', function() {
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                submitForm(this.parentElement);
            });
        });

        // Função para calcular frete
        async function calculateShippingCost() {
            const cepInput = document.getElementById('cep-input');
            const cep = cepInput.value.replace(/\D/g, '');
            const errorDiv = document.getElementById('cep-error');
            const loadingDiv = document.getElementById('cep-loading');
            const errorText = document.getElementById('cep-error-text');

            // Validar CEP
            if (!cep || cep.length !== 8) {
                errorDiv.style.display = 'flex';
                errorText.textContent = 'Digite um CEP válido com 8 dígitos';
                return;
            }

            errorDiv.style.display = 'none';
            loadingDiv.style.display = 'flex';

            try {
                // Chamar API de cálculo de frete
                const response = await fetch('{{ route("cart.calculate-shipping") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        cep: cep,
                        isShippingAddress: true
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Erro ao calcular frete');
                }

                // Atualizar valores na tela
                currentShippingCost = data.shippingCost;
                
                // Formatar valores para exibição
                const shippingFormatted = new Intl.NumberFormat('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(currentShippingCost);

                const total = subtotalAmount + currentShippingCost;
                const totalFormatted = new Intl.NumberFormat('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(total);

                // Atualizar elementos na tela
                const shippingElement = document.getElementById('shipping-amount');
                if (shippingElement) {
                    if (currentShippingCost === 0) {
                        shippingElement.innerHTML = '<span style="color: #10b981;">GRÁTIS 🎉</span>';
                        shippingElement.parentElement.style.color = '#10b981';
                    } else {
                        shippingElement.textContent = shippingFormatted;
                        shippingElement.parentElement.style.color = '#667eea';
                    }
                }
                
                document.getElementById('total-amount').textContent = totalFormatted;

                // Atualizar indicador de frete grátis
                updateFreeShippingIndicator();

                // Formatar CEP para exibição (XXXXX-XXX)
                cepInput.value = cep.substring(0, 5) + '-' + cep.substring(5);

                loadingDiv.style.display = 'none';
                errorDiv.style.display = 'none';

            } catch (error) {
                loadingDiv.style.display = 'none';
                errorDiv.style.display = 'flex';
                errorText.textContent = error.message || 'Erro ao calcular frete';
                console.error('Erro ao calcular frete:', error);
            }
        }

        // Permitir cálculo ao pressionar Enter no campo CEP
        document.getElementById('cep-input')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                calculateShippingCost();
            }
        });

        // Formatar CEP enquanto digita
        document.getElementById('cep-input')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });

        // Função para atualizar indicador de frete grátis
        function updateFreeShippingIndicator() {
            const freeShippingMinimum = {{ $freeShippingMinimum ?? 0 }};
            if (freeShippingMinimum <= 0) return;

            const subtotalText = document.getElementById('subtotal-amount').textContent;
            const subtotal = parseFloat(subtotalText.replace('.', '').replace(',', '.'));
            const indicator = document.getElementById('free-shipping-indicator');
            
            if (!indicator) return;

            if (subtotal >= freeShippingMinimum) {
                // Frete grátis conquistado
                indicator.innerHTML = `
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 16px; border-radius: 8px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fas fa-check-circle"></i>
                        <span style="font-weight: 600;">🎉 Parabéns! Você ganhou frete grátis!</span>
                    </div>
                `;
            } else {
                // Ainda não alcançou o mínimo
                const remaining = freeShippingMinimum - subtotal;
                const remainingFormatted = remaining.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                indicator.innerHTML = `
                    <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 12px 16px; border-radius: 8px; text-align: center;">
                        <div style="margin-bottom: 4px; font-weight: 600;">🚚 Frete grátis</div>
                        <div style="font-size: 14px;">Compre mais <strong>R$ ${remainingFormatted}</strong> e ganhe frete grátis!</div>
                    </div>
                `;
            }
        }

        // Atualizar indicador quando a página carrega
        updateFreeShippingIndicator();
    </script>
@endsection
