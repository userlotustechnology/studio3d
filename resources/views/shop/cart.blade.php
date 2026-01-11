@extends('shop.layout')

@section('title', 'Carrinho de Compras')

@section('content')
    <div class="container" style="padding: 40px 20px;">
        <div class="cart-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Cart Items -->
            <div class="cart-items-section">
                <h1 style="font-size: 32px; margin-bottom: 30px; color: var(--text-dark);">Carrinho de Compras</h1>

                @if (session('success'))
                    <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #065f46;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #7f1d1d;">
                        {{ session('error') }}
                    </div>
                @endif

                @if(count($items) > 0)
                    <div style="background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        @foreach($items as $item)
                            <div class="cart-item" style="display: grid; grid-template-columns: 100px 1fr auto auto; gap: 20px; align-items: center; padding: 20px; border-bottom: 1px solid var(--border-color);">
                                <!-- Product Image -->
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="cart-item-image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div class="cart-item-image" style="width: 100px; height: 100px; background-color: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-box" style="font-size: 32px; color: #9ca3af;"></i>
                                    </div>
                                @endif

                                <!-- Product Info -->
                                <div class="cart-item-info">
                                    <h3 style="font-weight: 600; margin-bottom: 8px; color: var(--text-dark);">
                                        @if($item->product)
                                            <a href="{{ route('shop.show', $item->product_id) }}" style="color: var(--primary-color);">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h3>
                                    <p class="cart-item-unit-price" style="color: var(--text-light); font-size: 14px;">
                                        R$ {{ number_format($item->product_price, 2, ',', '.') }} cada
                                    </p>
                                </div>

                                <!-- Quantity -->
                                <div class="cart-item-quantity" style="display: flex; align-items: center; gap: 10px;">
                                    <form method="POST" action="{{ route('cart.update', $item->product_id) }}" style="display: flex; gap: 10px; align-items: center;" class="quantity-form">
                                        @csrf
                                        <button type="button" class="qty-btn" onclick="decrementQuantity(this)" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; cursor: pointer; border-radius: 4px;">−</button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="qty-input" style="width: 50px; text-align: center; padding: 6px; border: 1px solid var(--border-color); border-radius: 4px;">
                                        <button type="button" class="qty-btn" onclick="incrementQuantity(this)" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; cursor: pointer; border-radius: 4px;">+</button>
                                    </form>
                                </div>

                                <!-- Total & Remove -->
                                <div class="cart-item-total" style="text-align: right;">
                                    <div style="font-size: 18px; font-weight: 700; color: var(--primary-color); margin-bottom: 10px;">
                                        R$ {{ number_format($item->product_price * $item->quantity, 2, ',', '.') }}
                                    </div>
                                    <form method="POST" action="{{ route('cart.remove', $item->product_id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: 500; text-decoration: underline;">
                                            Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 20px; text-align: right;">
                        <form method="POST" action="{{ route('cart.clear') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: var(--text-light); cursor: pointer; text-decoration: underline; font-weight: 500;">
                                Limpar Carrinho
                            </button>
                        </form>
                    </div>
                @else
                    <div style="background-color: var(--bg-light); padding: 60px 20px; text-align: center; border-radius: 8px;">
                        <i class="fas fa-shopping-cart" style="font-size: 48px; color: var(--border-color); margin-bottom: 20px;"></i>
                        <h2 style="color: var(--text-dark); margin-bottom: 10px;">Carrinho Vazio</h2>
                        <p style="color: var(--text-light); margin-bottom: 20px;">Você ainda não adicionou nenhum produto ao carrinho.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Continuar Comprando</a>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            @if(count($items) > 0)
                <div class="cart-summary-section">
                    <div class="cart-summary" style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                        <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--text-dark);">Resumo do Pedido</h2>

                        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: var(--text-light);">Subtotal</span>
                                <span style="font-weight: 600;">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-light);">Frete</span>
                                <span style="font-weight: 600;">R$ {{ number_format($shippingCost, 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <div style="background-color: var(--bg-light); padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 18px; font-weight: 700;">Total</span>
                                <span style="font-size: 24px; font-weight: 700; color: var(--primary-color);">
                                    R$ {{ number_format($total, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary" style="width: 100%; padding: 15px; text-align: center; display: block; margin-bottom: 10px;">
                            <i class="fas fa-lock"></i> Ir para Checkout
                        </a>
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary" style="width: 100%; padding: 15px; text-align: center; display: block;">
                            Continuar Comprando
                        </a>

                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color); font-size: 13px; color: var(--text-light); text-align: center;">
                            <i class="fas fa-lock-open"></i> Pagamento seguro
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Cart Page Responsive Styles */
        @media (max-width: 992px) {
            .cart-layout {
                gap: 30px !important;
            }
        }
        
        @media (max-width: 768px) {
            .cart-layout {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            
            .cart-items-section h1 {
                font-size: 24px !important;
            }
            
            .cart-item {
                grid-template-columns: 80px 1fr !important;
                grid-template-rows: auto auto !important;
                gap: 15px !important;
                padding: 15px !important;
            }
            
            .cart-item-image {
                width: 80px !important;
                height: 80px !important;
            }
            
            .cart-item-info {
                grid-column: 2 !important;
            }
            
            .cart-item-info h3 {
                font-size: 14px !important;
            }
            
            .cart-item-unit-price {
                font-size: 13px !important;
            }
            
            .cart-item-quantity {
                grid-column: 1 / -1 !important;
                justify-content: center !important;
            }
            
            .cart-item-total {
                grid-column: 1 / -1 !important;
                text-align: center !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding-top: 10px !important;
                border-top: 1px dashed var(--border-color) !important;
            }
            
            .cart-summary {
                position: static !important;
            }
            
            .cart-summary h2 {
                font-size: 18px !important;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 25px 15px !important;
            }
            
            .cart-items-section h1 {
                font-size: 22px !important;
                margin-bottom: 20px !important;
            }
            
            .cart-item {
                grid-template-columns: 70px 1fr !important;
                gap: 12px !important;
                padding: 12px !important;
            }
            
            .cart-item-image {
                width: 70px !important;
                height: 70px !important;
            }
            
            .qty-btn {
                padding: 5px 8px !important;
            }
            
            .qty-input {
                width: 45px !important;
                padding: 5px !important;
            }
            
            .cart-summary {
                padding: 20px !important;
            }
        }
    </style>

    <script>
        // Funções para incrementar/decrementar quantidade
        function incrementQuantity(btn) {
            const input = btn.parentElement.querySelector('.qty-input');
            input.value = parseInt(input.value) + 1;
            submitForm(btn.parentElement);
        }

        function decrementQuantity(btn) {
            const input = btn.parentElement.querySelector('.qty-input');
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
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                submitForm(this.parentElement);
            });
        });
    </script>
@endsection
