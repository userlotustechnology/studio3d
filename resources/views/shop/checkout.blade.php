@extends('shop.layout')

@section('title', 'Finalizar Compra')

@section('content')
    <!-- Hero Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 20px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(50%, -50%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(-30%, 30%);"></div>
        
        <div class="container" style="position: relative; z-index: 1;">
            <h1 style="font-size: 42px; font-weight: 800; margin-bottom: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <i class="fas fa-lock"></i> Finalizar Compra
            </h1>
            <p style="font-size: 18px; opacity: 0.95; margin: 0;">Conclua seu pedido com segurança</p>
        </div>
    </div>

    <div class="container" style="padding: 40px 20px;">
        <div class="checkout-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Checkout Form -->
            <div class="checkout-form-section">
                <form method="POST" action="{{ route('cart.process-checkout') }}" class="checkout-form" style="background: white; border-radius: 12px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
                    @csrf

                    <!-- Customer Information -->
                    <h2 style="font-size: 22px; margin-bottom: 25px; color: var(--text-dark); font-weight: 700; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-circle" style="color: #667eea;"></i> Informações Pessoais
                    </h2>

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
                    <h2 style="font-size: 22px; margin-bottom: 25px; margin-top: 40px; color: var(--text-dark); font-weight: 700; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea;"></i> Endereço de Cobrança
                    </h2>

                    <!-- CEP First -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">CEP *</label>
                        <div style="position: relative;">
                            <input 
                                type="text" 
                                id="billing_postal_code" 
                                name="billing_postal_code" 
                                value="{{ old('billing_postal_code') }}" 
                                required 
                                placeholder="00000-000" 
                                style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 6px; font-family: inherit; transition: border-color 0.3s;"
                                maxlength="9">
                            <span id="cep_loader" style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #667eea;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                            <span id="cep_check" style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #10b981; font-size: 18px;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                        <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Os dados serão preenchidos automaticamente</small>
                        <span id="cep_error" style="color: #ef4444; font-size: 14px; display: none;"></span>
                        @error('billing_postal_code')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Other Address Fields (Hidden until CEP is filled) -->
                    <div id="billing_address_fields" style="display: none;">
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
                    <div id="shipping_address_section" style="display: none; margin-bottom: 20px; padding: 25px; background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; margin-bottom: 25px; color: var(--text-dark); font-weight: 700; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-truck" style="color: #667eea;"></i> Endereço de Entrega
                        </h2>
                    @else
                    <!-- Shipping Address Info for Digital Products -->
                    @if($hasDigitalProducts)
                    <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 30px; margin-top: 30px; border-radius: 4px; color: #065f46;">
                        <i class="fas fa-info-circle"></i> 
                        <span style="font-weight: 600;">Este pedido contém produtos digitais</span> e não requer endereço de entrega.
                    </div>
                    @endif

                    <!-- Shipping Address (Hidden by default) -->
                    <div id="shipping_address_section" style="display: none; margin-bottom: 20px; padding: 25px; background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <h2 style="font-size: 22px; margin-bottom: 25px; color: var(--text-dark); font-weight: 700; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-truck" style="color: #667eea;"></i> Endereço de Entrega
                        </h2>
                    @endif

                        <div style="margin-bottom: 20px; position: relative;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">CEP *</label>
                            <input type="text" id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" placeholder="00000-000" style="width: 100%; padding: 12px; padding-right: 40px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            <div id="shipping_cep_loader" style="display: none; position: absolute; right: 12px; top: 40px; width: 20px; height: 20px; border: 2px solid #667eea; border-top: 2px solid transparent; border-radius: 50%; animation: spin 0.6s linear infinite;"></div>
                            <div id="shipping_cep_check" style="display: none; position: absolute; right: 12px; top: 40px; color: #10b981;"><i class="fas fa-check-circle"></i></div>
                            <div id="shipping_cep_error" style="display: none; color: #ef4444; font-size: 14px; margin-top: 5px;"></div>
                            @error('shipping_postal_code')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="shipping_address_fields" style="display: none;">
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
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <h2 style="font-size: 22px; margin-bottom: 25px; margin-top: 40px; color: var(--text-dark); font-weight: 700; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-credit-card" style="color: #667eea;"></i> Método de Pagamento
                    </h2>

                    <div style="display: grid; gap: 15px; margin-bottom: 30px;">
                        <label style="display: flex; align-items: center; padding: 18px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.3s; background: white;">
                            <input type="radio" name="payment_method" value="credit_card" required style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: var(--text-dark); font-size: 16px;"><i class="fas fa-credit-card" style="color: #667eea; margin-right: 8px;"></i> Cartão de Crédito</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Parcelado em até 12x com juros</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; padding: 18px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.3s; background: white;">
                            <input type="radio" name="payment_method" value="pix" required style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: var(--text-dark); font-size: 16px;"><i class="fas fa-qrcode" style="color: #667eea; margin-right: 8px;"></i> PIX</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Transferência instantânea</div>
                            </div>
                        </label>

                        <label style="display: flex; align-items: center; padding: 18px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.3s; background: white;">
                            <input type="radio" name="payment_method" value="boleto" required style="margin-right: 15px; width: 20px; height: 20px; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: var(--text-dark); font-size: 16px;"><i class="fas fa-barcode" style="color: #667eea; margin-right: 8px;"></i> Boleto</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Vencimento em 3 dias úteis</div>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                        <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #7f1d1d;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="checkout-buttons" style="display: grid; gap: 12px; margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                            <i class="fas fa-check-circle"></i> Confirmar Pedido
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary" style="width: 100%; padding: 16px; text-align: center; display: block; font-weight: 700; border-radius: 10px; border: 2px solid #667eea; color: #667eea; background: white; transition: all 0.3s; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> Voltar ao Carrinho
                        </a>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="checkout-summary-section">
                <div class="checkout-summary" style="background: white; border-radius: 12px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: sticky; top: 100px; border: 1px solid #e5e7eb;">
                    <h2 style="font-size: 22px; margin-bottom: 25px; color: var(--text-dark); font-weight: 700; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-receipt" style="color: #667eea;"></i> Resumo do Pedido
                    </h2>

                    <div class="checkout-items-list">
                        @foreach($items as $item)
                            <div class="checkout-item-modern">
                                <div class="checkout-item-image">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                </div>
                                <div class="checkout-item-info">
                                    <div class="checkout-item-name">{{ $item->product->name }}</div>
                                    <div class="checkout-item-qty">Qtd: <strong>{{ $item->quantity }}</strong></div>
                                </div>
                                <div class="checkout-item-price">
                                    R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="border-top: 2px solid var(--border-color); padding-top: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                            <span style="color: var(--text-light); font-weight: 500;">Subtotal</span>
                            <span style="font-weight: 700; color: var(--text-dark);">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                            <span style="color: var(--text-light); font-weight: 500;">Frete</span>
                            <span style="font-weight: 700; color: var(--text-dark);" data-frete-value>R$ {{ number_format($shippingCost, 2, ',', '.') }}</span>
                        </div>
                        @if($discount > 0)
                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                                <span style="color: var(--text-light); font-weight: 500;">Desconto</span>
                                <span style="font-weight: 700; color: #10b981;">-R$ {{ number_format($discount, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); padding: 20px 15px; border-radius: 10px; margin-top: 15px;">
                            <span style="font-size: 18px; font-weight: 700; color: var(--text-dark);">Total</span>
                            <span style="font-size: 28px; font-weight: 800; color: #667eea;" data-total-value data-subtotal="{{ $subtotal }}">
                                R$ {{ number_format($total, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div style="margin-top: 25px; padding: 18px; background-color: #f0fdf4; border-radius: 10px; font-size: 13px; color: #065f46; text-align: center; border: 1px solid #bbf7d0; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <i class="fas fa-shield-alt"></i> Seus dados estão seguros e criptografados
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Checkout Items List */
        .checkout-items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
            padding: 0;
        }

        .checkout-item-modern {
            display: grid;
            grid-template-columns: 70px 1fr auto;
            gap: 15px;
            align-items: center;
            padding: 15px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .checkout-item-modern:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .checkout-item-image {
            width: 70px;
            height: 70px;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }

        .checkout-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .checkout-item-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .checkout-item-name {
            font-weight: 700;
            font-size: 14px;
            color: #1f2937;
            line-height: 1.3;
        }

        .checkout-item-qty {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .checkout-item-price {
            font-weight: 800;
            font-size: 16px;
            color: #667eea;
            text-align: right;
            white-space: nowrap;
            padding-left: 10px;
            min-width: 90px;
            flex-shrink: 0;
        }

        /* Checkout Page Responsive Styles */
        @media (max-width: 992px) {
            .checkout-layout {
                gap: 30px !important;
            }
        }
        
        @media (max-width: 768px) {
            .checkout-layout {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            
            .checkout-form {
                padding: 25px !important;
            }
            
            .checkout-form h2 {
                font-size: 20px !important;
            }
            
            .form-row {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }
            
            .checkout-summary {
                position: static !important;
                padding: 25px !important;
            }
            
            .checkout-summary h2 {
                font-size: 20px !important;
            }
            
            .checkout-summary-section {
                order: -1;
            }
            
            .payment-methods label {
                padding: 16px !important;
            }

            .checkout-item-modern {
                grid-template-columns: 60px 1fr auto;
                gap: 12px;
                padding: 12px;
            }

            .checkout-item-image {
                width: 60px;
                height: 60px;
            }

            .checkout-item-name {
                font-size: 13px;
            }

            .checkout-item-price {
                font-size: 14px;
                min-width: 80px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 25px 12px !important;
            }
            
            .checkout-layout {
                gap: 20px !important;
            }
            
            .checkout-form {
                padding: 20px !important;
            }
            
            .checkout-form input,
            .checkout-form select {
                padding: 12px !important;
                font-size: 16px !important;
            }
            
            .checkout-buttons button,
            .checkout-buttons a {
                padding: 14px !important;
                font-size: 15px !important;
            }
            
            .checkout-summary {
                padding: 20px !important;
            }

            .checkout-item-modern {
                grid-template-columns: 55px 1fr auto;
                gap: 10px;
                padding: 10px;
            }

            .checkout-item-image {
                width: 55px;
                height: 55px;
            }

            .checkout-item-name {
                font-size: 12px;
            }

            .checkout-item-qty {
                font-size: 11px;
            }

            .checkout-item-price {
                font-size: 13px;
                min-width: 70px;
            }
        }
    </style>

    <script>
        // Integração com ViaCEP para consulta automática de CEP
        const cepInput = document.getElementById('billing_postal_code');
        const cepLoader = document.getElementById('cep_loader');
        const cepCheck = document.getElementById('cep_check');
        const cepError = document.getElementById('cep_error');
        const billingAddressFields = document.getElementById('billing_address_fields');

        // Máscara para CEP (formato: 00000-000)
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.slice(0, 5) + '-' + value.slice(5, 8);
            }
            e.target.value = value;

            // Se tiver 8 dígitos (completo), faz a busca
            if (value.replace('-', '').length === 8) {
                searchCEP(value.replace('-', ''));
            } else {
                cepLoader.style.display = 'none';
                cepCheck.style.display = 'none';
                cepError.style.display = 'none';
                billingAddressFields.style.display = 'none';
            }
        });

        function searchCEP(cep) {
            cepLoader.style.display = 'inline';
            cepCheck.style.display = 'none';
            cepError.style.display = 'none';

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    cepLoader.style.display = 'none';

                    if (data.erro) {
                        cepError.textContent = 'CEP não encontrado';
                        cepError.style.display = 'block';
                        cepCheck.style.display = 'none';
                        billingAddressFields.style.display = 'none';
                        return;
                    }

                    // Preencher os campos
                    document.querySelector('input[name="billing_street"]').value = data.logradouro;
                    document.querySelector('input[name="billing_neighborhood"]').value = data.bairro;
                    document.querySelector('input[name="billing_city"]').value = data.localidade;
                    document.querySelector('input[name="billing_state"]').value = data.uf;

                    // Mostrar campos de endereço de cobrança
                    billingAddressFields.style.display = 'block';
                    cepCheck.style.display = 'inline';
                    cepError.style.display = 'none';

                    // Se houver endereço de entrega visível, preencher também
                    const shippingSection = document.getElementById('shipping_address_section');
                    if (shippingSection.style.display !== 'none') {
                        const shippingStreet = document.querySelector('input[name="shipping_street"]');
                        const shippingNeighborhood = document.querySelector('input[name="shipping_neighborhood"]');
                        const shippingCity = document.querySelector('input[name="shipping_city"]');
                        const shippingState = document.querySelector('input[name="shipping_state"]');

                        if (shippingStreet && !shippingStreet.value) {
                            shippingStreet.value = data.logradouro;
                            shippingNeighborhood.value = data.bairro;
                            shippingCity.value = data.localidade;
                            shippingState.value = data.uf;
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao consultar CEP:', error);
                    cepLoader.style.display = 'none';
                    cepError.textContent = 'Erro ao consultar CEP. Tente novamente.';
                    cepError.style.display = 'block';
                    billingAddressFields.style.display = 'none';
                });
        }

        // Integração com ViaCEP para endereço de entrega
        const shippingCepInput = document.getElementById('shipping_postal_code');
        const shippingCepLoader = document.getElementById('shipping_cep_loader');
        const shippingCepCheck = document.getElementById('shipping_cep_check');
        const shippingCepError = document.getElementById('shipping_cep_error');
        const shippingAddressFields = document.getElementById('shipping_address_fields');

        if (shippingCepInput) {
            // Máscara para CEP de entrega (formato: 00000-000)
            shippingCepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    value = value.slice(0, 5) + '-' + value.slice(5, 8);
                }
                e.target.value = value;

                // Se tiver 8 dígitos (completo), faz a busca
                if (value.replace('-', '').length === 8) {
                    searchShippingCEP(value.replace('-', ''));
                } else {
                    shippingCepLoader.style.display = 'none';
                    shippingCepCheck.style.display = 'none';
                    shippingCepError.style.display = 'none';
                    shippingAddressFields.style.display = 'none';
                }
            });
        }

        function searchShippingCEP(cep) {
            shippingCepLoader.style.display = 'inline';
            shippingCepCheck.style.display = 'none';
            shippingCepError.style.display = 'none';

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    shippingCepLoader.style.display = 'none';

                    if (data.erro) {
                        shippingCepError.textContent = 'CEP não encontrado';
                        shippingCepError.style.display = 'block';
                        shippingCepCheck.style.display = 'none';
                        shippingAddressFields.style.display = 'none';
                        return;
                    }

                    // Preencher os campos de entrega
                    document.querySelector('input[name="shipping_street"]').value = data.logradouro;
                    document.querySelector('input[name="shipping_neighborhood"]').value = data.bairro;
                    document.querySelector('input[name="shipping_city"]').value = data.localidade;
                    document.querySelector('input[name="shipping_state"]').value = data.uf;

                    // Calcular frete baseado no CEP
                    calculateShippingCost(cep);

                    // Mostrar campos de endereço de entrega
                    shippingAddressFields.style.display = 'block';
                    shippingCepCheck.style.display = 'inline';
                    shippingCepError.style.display = 'none';
                })
                .catch(error => {
                    console.error('Erro ao consultar CEP de entrega:', error);
                    shippingCepLoader.style.display = 'none';
                    shippingCepError.textContent = 'Erro ao consultar CEP. Tente novamente.';
                    shippingCepError.style.display = 'block';
                    shippingAddressFields.style.display = 'none';
                });
        }

        // Função para calcular frete baseado no CEP
        function calculateShippingCost(cep) {
            fetch('{{ route("cart.calculate-shipping") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cep: cep,
                    isShippingAddress: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.shippingCost !== undefined) {
                    // Atualizar o valor do frete na tela
                    const freteElement = document.querySelector('[data-frete-value]');
                    if (freteElement) {
                        freteElement.textContent = data.formattedCost;
                    }
                    
                    // Atualizar o total
                    const totalElement = document.querySelector('[data-total-value]');
                    if (totalElement) {
                        const subtotal = parseFloat(totalElement.getAttribute('data-subtotal'));
                        const newTotal = subtotal + data.shippingCost;
                        totalElement.textContent = 'R$ ' + newTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    }
                }
            })
            .catch(error => console.error('Erro ao calcular frete:', error));
        }

        // Funções existentes do formulário
        const phoneInput = document.querySelector('input[name="customer_phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length <= 2) {
                        value = `(${value}`;
                    } else if (value.length <= 7) {
                        value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
                    } else {
                        value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7, 11)}`;
                    }
                }
                e.target.value = value;
            });
        }

        // Toggle shipping address section
        const differentAddressCheckbox = document.getElementById('different_address_toggle');
        const shippingAddressSection = document.getElementById('shipping_address_section');
        const shippingInputs = shippingAddressSection.querySelectorAll('input[name^="shipping_"]');

        // Se há erros de validação e o checkbox está marcado, mostrar a seção
        if (differentAddressCheckbox && differentAddressCheckbox.checked) {
            shippingAddressSection.style.display = 'block';
        }

        if (differentAddressCheckbox) {
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
        }
    </script>
@endsection
