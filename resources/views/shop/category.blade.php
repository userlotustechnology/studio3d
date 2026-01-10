@extends('shop.layout')

@section('title', $category . ' - Loja Online')

@section('content')
    <!-- Category Header -->
    <div class="hero">
        <div class="container">
            <h1>{{ $category }}</h1>
            <p>Confira todos os produtos dessa categoria</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Categories Filter -->
        @if($categories->count() > 0)
            <div class="categories">
                <a href="{{ route('shop.index') }}" class="category-btn">
                    <i class="fas fa-th"></i> Todos os Produtos
                </a>
                @foreach($categories as $categoryName)
                    <a href="{{ route('shop.category', $categoryName) }}" class="category-btn {{ $categoryName === $category ? 'active' : '' }}">
                        {{ $categoryName }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                        <div class="product-info">
                            <div class="product-category">{{ $product->category }}</div>
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                            <div class="product-footer">
                                <div class="product-price">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </div>
                                <button class="btn-add-cart" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                    <i class="fas fa-shopping-cart"></i> Comprar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h2>Nenhum produto encontrado</h2>
                <p>Desculpe, não encontramos produtos nessa categoria no momento.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="display: inline-block; margin-top: 20px;">
                    Ver Todos os Produtos
                </a>
            </div>
        @endif
    </div>
@endsection
