@extends('shop.layout')

@section('title', 'Carrinho de Compras')

@section('content')
    <div class="container" style="padding: 40px 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
            <!-- Cart Items -->
            <div>
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
                            <div style="display: grid; grid-template-columns: 100px 1fr auto auto; gap: 20px; align-items: center; padding: 20px; border-bottom: 1px solid var(--border-color);">
                                <!-- Product Image -->
                                <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;">

                                <!-- Product Info -->
                                <div>
                                    <h3 style="font-weight: 600; margin-bottom: 8px; color: var(--text-dark);">
                                        <a href="{{ route('shop.show', $item['product']->id) }}" style="color: var(--primary-color);">
                                            {{ $item['product']->name }}
                                        </a>
                                    </h3>
                                    <p style="color: var(--text-light); font-size: 14px;">
                                        R$ {{ number_format($item['product']->price, 2, ',', '.') }} cada
                                    </p>
                                </div>

                                <!-- Quantity -->
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <form method="POST" action="{{ route('cart.update', $item['product']->id) }}" style="display: flex; gap: 10px; align-items: center;">
                                        @csrf
                                        <button type="button" onclick="this.parentElement.quantity.value = Math.max(1, parseInt(this.parentElement.quantity.value) - 1)" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; cursor: pointer; border-radius: 4px;">−</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width: 50px; text-align: center; padding: 6px; border: 1px solid var(--border-color); border-radius: 4px;">
                                        <button type="button" onclick="this.parentElement.quantity.value = parseInt(this.parentElement.quantity.value) + 1" style="background: none; border: 1px solid var(--border-color); padding: 6px 10px; cursor: pointer; border-radius: 4px;">+</button>
                                        <button type="submit" class="btn btn-primary" style="padding: 8px 15px; font-size: 14px;">Atualizar</button>
                                    </form>
                                </div>

                                <!-- Total & Remove -->
                                <div style="text-align: right;">
                                    <div style="font-size: 18px; font-weight: 700; color: var(--primary-color); margin-bottom: 10px;">
                                        R$ {{ number_format($item['total'], 2, ',', '.') }}
                                    </div>
                                    <form method="POST" action="{{ route('cart.remove', $item['product']->id) }}" style="display: inline;">
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
                <div>
                    <div style="background-color: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 100px;">
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
@endsection
