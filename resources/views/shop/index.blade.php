@extends('shop.layout')

@section('title', 'Loja Online - Produtos')

@section('content')
    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1>Bem-vindo à Nossa Loja</h1>
            <p>Descubra produtos de qualidade com os melhores preços</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Categories Filter -->
        @if($categories->count() > 0)
            <div class="categories">
                <a href="{{ route('shop.index') }}" class="category-btn active">
                    <i class="fas fa-th"></i> Todos os Produtos
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('shop.category', $category->id) }}" class="category-btn">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('shop.show', $product->id) }}" style="display: block; text-decoration: none; color: inherit;">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                            <div class="product-info">
                                <div class="product-category">{{ $product->category?->name ?? 'Sem categoria' }}</div>
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                                <div class="product-footer">
                                    <div class="product-price">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </div>
                                    <button type="button" class="btn-add-cart" onclick="addToCart({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }}); return false;">
                                        <i class="fas fa-shopping-cart"></i> Comprar
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h2>Nenhum produto encontrado</h2>
                <p>Desculpe, não encontramos produtos no momento.</p>
            </div>
        @endif
    </div>
@endsection
