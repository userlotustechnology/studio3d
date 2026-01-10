@extends('shop.layout')

@section('title', 'Finalizar Compra')

@section('content')
    <div class="container" style="padding: 40px 20px;">
        <h1 style="font-size: 32px; margin-bottom: 30px; color: var(--text-dark);">Finalizar Compra</h1>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Checkout Form -->
            <div>
                <form method="POST" action="{{ route('cart.process-checkout') }}" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    @csrf

                    <!-- Personal Information -->
                    <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Informações Pessoais</h2>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Nome Completo *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('customer_name')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Email *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('customer_email')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Telefone *</label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="(11) 98765-4321" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('customer_phone')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Shipping Address -->
                    <h2 style="font-size: 20px; margin-bottom: 20px; margin-top: 40px; color: var(--text-dark);">Endereço de Entrega</h2>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Endereço *</label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required placeholder="Rua, número e complemento" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('shipping_address')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Cidade *</label>
                            <input type="text" name="city" value="{{ old('city') }}" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('city')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Estado *</label>
                            <input type="text" name="state" value="{{ old('state') }}" required maxlength="2" placeholder="SP" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                            @error('state')
                                <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">CEP *</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required placeholder="00000-000" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 6px; font-family: inherit;">
                        @error('postal_code')
                            <span style="color: #ef4444; font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <h2 style="font-size: 20px; margin-bottom: 20px; margin-top: 40px; color: var(--text-dark);">Método de Pagamento</h2>

                    <div style="display: grid; gap: 15px; margin-bottom: 30px;">
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="credit_card" required style="margin-right: 15px;">
                            <div>
                                <div style="font-weight: 600; color: var(--text-dark);"><i class="fas fa-credit-card"></i> Cartão de Crédito</div>
                                <div style="font-size: 13px; color: var(--text-light); margin-top: 5px;">Parcelado em até 12x</div>
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

                    <div style="display: grid; gap: 10px;">
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
            <div>
                <div style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                    <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Resumo do Pedido</h2>

                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                        @foreach($items as $item)
                            <div style="display: flex; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color);">
                                <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
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
@endsection
