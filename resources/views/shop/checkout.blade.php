@extends('shop.layout')

@section('title', 'Finalizar Compra')

@section('content')
    <div class="container" style="padding: 40px 20px;">
        <h1 class="checkout-title" style="font-size: 32px; margin-bottom: 30px; color: var(--text-dark);">Finalizar Compra</h1>

        <div class="checkout-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Checkout Form -->
            <div class="checkout-form-section">
                <form method="POST" action="{{ route('cart.process-checkout') }}" class="checkout-form" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    @csrf

                    <!-- Customer Information -->
                    <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Informações Pessoais</h2>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Nome Completo *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $customer->name ?? '') }}" required placeholder="Seu nome completo" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('customer_name')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Email *</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $customer->email ?? '') }}" required placeholder="seu@email.com" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('customer_email')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Telefone *</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone', $customer->phone ?? '') }}" required placeholder="(11) 99999-9999" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('customer_phone')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <h2 style="font-size: 20px; margin-bottom: 20px; margin-top: 40px; color: var(--text-dark);">Endereço de Cobrança</h2>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Rua *</label>
                        <input type="text" name="billing_street" value="{{ old('billing_street') }}" required placeholder="Nome da rua" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('billing_street')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Número *</label>
                            <input type="text" name="billing_number" value="{{ old('billing_number') }}" required placeholder="000" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('billing_number')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Complemento</label>
                            <input type="text" name="billing_complement" value="{{ old('billing_complement') }}" placeholder="Apto, sala, etc." style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('billing_complement')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Bairro *</label>
                        <input type="text" name="billing_neighborhood" value="{{ old('billing_neighborhood') }}" required placeholder="Nome do bairro" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('billing_neighborhood')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Cidade *</label>
                            <input type="text" name="billing_city" value="{{ old('billing_city') }}" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('billing_city')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Estado *</label>
                            <input type="text" name="billing_state" value="{{ old('billing_state') }}" required maxlength="2" placeholder="SP" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('billing_state')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">CEP *</label>
                        <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code') }}" required placeholder="00000-000" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('billing_postal_code')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Shipping Address Toggle (Only for physical products) -->
                    @if($hasPhysicalProducts)
                    <div style="margin-bottom: 20px; margin-top: 30px;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" id="different_address_toggle" name="different_address" value="on" style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-weight: 600; color: var(--text-dark);">O endereço de entrega é diferente do de cobrança?</span>
                        </label>
                    </div>

                    <!-- Shipping Address (Hidden by default) -->
                    <div id="shipping_address_section" style="display: none; margin-bottom: 20px;">
                        <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Endereço de Entrega</h2>
                    @else
                    <!-- Shipping Address Info for Digital Products -->
                    @if($hasDigitalProducts)
                    <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 30px; margin-top: 30px; border-radius: 4px; color: #065f46;">
                        <i class="fas fa-info-circle"></i> 
                        <span style="font-weight: 600;">Este pedido contém produtos digitais</span> e não requer endereço de entrega.
                    </div>
                    @endif

                    <!-- Shipping Address (Hidden by default) -->
                    <div id="shipping_address_section" style="display: none; margin-bottom: 20px;">
                        <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Endereço de Entrega</h2>
                    @endif

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Rua *</label>
                            <input type="text" name="shipping_street" value="{{ old('shipping_street') }}" placeholder="Nome da rua" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('shipping_street')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Número *</label>
                                <input type="text" name="shipping_number" value="{{ old('shipping_number') }}" placeholder="000" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                                @error('shipping_number')
                                    <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Complemento</label>
                                <input type="text" name="shipping_complement" value="{{ old('shipping_complement') }}" placeholder="Apto, sala, etc." style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                                @error('shipping_complement')
                                    <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Bairro *</label>
                            <input type="text" name="shipping_neighborhood" value="{{ old('shipping_neighborhood') }}" placeholder="Nome do bairro" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('shipping_neighborhood')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Cidade *</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                                @error('shipping_city')
                                    <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Estado *</label>
                                <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" maxlength="2" placeholder="SP" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                                @error('shipping_state')
                                    <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">CEP *</label>
                            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" placeholder="00000-000" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('shipping_postal_code')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <h2 style="font-size: 20px; margin-bottom: 20px; margin-top: 40px; color: var(--text-dark);">Método de Pagamento</h2>

                    <div style="display: grid; gap: 15px; margin-bottom: 30px;">
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="credit_card" required style="margin-right: 15px;">
                            <div>
                                <div style="font-weight: 600; color: var(--text-dark);"><i class="fas fa-credit-card"></i> Cartão de Crédito</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Parcelado em até 12x com juros</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="pix" required style="margin-right: 15px;">
                            <div>
                                <div style="font-weight: 600; color: var(--text-dark);"><i class="fas fa-qrcode"></i> PIX</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Transferência instantânea</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="boleto" required style="margin-right: 15px;">
                            <div>
                                <div style="font-weight: 600; color: var(--text-dark);"><i class="fas fa-barcode"></i> Boleto</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Vencimento em 3 dias úteis</div>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                        <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #7f1d1d;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="checkout-buttons" style="display: grid; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">
                            <i class="fas fa-check-circle"></i> Confirmar Pedido
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary" style="width: 100%; padding: 15px; text-align: center; display: block;">
                            Voltar ao Carrinho
                        </a>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="checkout-summary-section">
                <div class="checkout-summary" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                    <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Resumo do Pedido</h2>

                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                        @foreach($items as $item)
                            <div style="display: flex; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color);">
                                <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; font-size: 14px; color: var(--text-dark);">{{ $item['product']->name }}</div>
                                    <div style="font-size: 13px; color: var(--text-light);">Qtd: {{ $item['quantity'] }}</div>
                                    <div style="font-weight: 600; color: var(--primary-color);">R$ {{ number_format($item['total'], 2, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="border-top: 2px solid var(--border-color); padding-top: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: var(--text-light);">Subtotal</span>
                            <span style="font-weight: 600;">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: var(--text-light);">Frete</span>
                            <span style="font-weight: 600;">R$ {{ number_format($shippingCost, 2, ',', '.') }}</span>
                        </div>
                        @if($discount > 0)
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: var(--text-light);">Desconto</span>
                                <span style="font-weight: 600; color: #10b981;">-R$ {{ number_format($discount, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-top: 1px solid var(--border-color); margin-top: 15px;">
                            <span style="font-size: 16px; font-weight: 700;">Total</span>
                            <span style="font-size: 24px; font-weight: 700; color: var(--primary-color);">
                                R$ {{ number_format($total, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding: 15px; background-color: var(--bg-light); border-radius: 6px; font-size: 13px; color: var(--text-light); text-align: center;">
                        <i class="fas fa-shield-alt"></i> Seus dados estão seguros e criptografados
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Máscara de Telefone Celular
        const phoneInput = document.querySelector('input[name="customer_phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 11) {
                    value = value.slice(0, 11);
                }
                
                if (value.length > 7) {
                    value = value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
                    value = '(' + value;
                } else if (value.length > 2) {
                    value = value.slice(0, 2) + ') ' + value.slice(2);
                    value = '(' + value;
                }
                
                e.target.value = value;
            });
        }

        // Toggle shipping address section
        const differentAddressCheckbox = document.getElementById('different_address_toggle');
        const shippingAddressSection = document.getElementById('shipping_address_section');
        const shippingInputs = shippingAddressSection.querySelectorAll('input[name^="shipping_"]');

        // Se há erros de validação e o checkbox está marcado, mostrar a seção
        if (differentAddressCheckbox.checked) {
            shippingAddressSection.style.display = 'block';
        }

        differentAddressCheckbox.addEventListener('change', function() {
            if (this.checked) {
                shippingAddressSection.style.display = 'block';
                // Tornar os inputs obrigatórios
                shippingInputs.forEach(input => input.required = true);
            } else {
                shippingAddressSection.style.display = 'none';
                // Remover obrigatoriedade quando desabilitado
                shippingInputs.forEach(input => input.required = false);
            }
        });
    </script>

    <style>
        /* Checkout Page Responsive Styles */
        @media (max-width: 992px) {
            .checkout-layout {
                gap: 30px !important;
            }
        }
        
        @media (max-width: 768px) {
            .checkout-title {
                font-size: 24px !important;
            }
            
            .checkout-layout {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            
            .checkout-form {
                padding: 20px !important;
            }
            
            .checkout-form h2 {
                font-size: 18px !important;
            }
            
            .form-row {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }
            
            .checkout-summary {
                position: static !important;
                padding: 20px !important;
            }
            
            .checkout-summary h2 {
                font-size: 18px !important;
            }
            
            .checkout-summary-section {
                order: -1;
            }
            
            .payment-methods label {
                padding: 12px !important;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 25px 12px !important;
            }
            
            .checkout-title {
                font-size: 22px !important;
                margin-bottom: 20px !important;
            }
            
            .checkout-form {
                padding: 15px !important;
            }
            
            .checkout-form input,
            .checkout-form select {
                padding: 10px !important;
                font-size: 16px !important;
            }
            
            .checkout-buttons button,
            .checkout-buttons a {
                padding: 12px !important;
                font-size: 14px !important;
            }
            
            .checkout-summary {
                padding: 15px !important;
            }
        }
    </style>
@endsection
