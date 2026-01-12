@extends('shop.layout')

@section('title', 'Loja Online - Produtos')

@section('content')
    <!-- Hero Section Moderna -->
    <div class="hero-modern">
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        <div class="container hero-content">
            <span class="hero-badge">🛒 Nova Coleção Disponível</span>
            <h1>Descubra Produtos <span class="gradient-text">Incríveis</span></h1>
            <p>Qualidade premium, preços justos e entrega rápida para você</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $products->count() }}+</span>
                    <span class="stat-label">Produtos</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number">{{ $categories->count() }}</span>
                    <span class="stat-label">Categorias</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number">⭐ 4.9</span>
                    <span class="stat-label">Avaliação</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Search & Filter Bar -->
        <div class="filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar produtos..." id="searchInput" onkeyup="filterProducts()">
            </div>
            <div class="filter-options">
                <select id="sortSelect" onchange="sortProducts()">
                    <option value="default">Ordenar por</option>
                    <option value="price-low">Menor Preço</option>
                    <option value="price-high">Maior Preço</option>
                    <option value="name">Nome A-Z</option>
                </select>
            </div>
        </div>

        <!-- Categories Filter Pills -->
        @if($categories->count() > 0)
            <div class="categories-modern">
                <a href="{{ route('shop.index') }}" class="category-pill {{ !request()->route('category') ? 'active' : '' }}">
                    <i class="fas fa-fire"></i> Todos
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('shop.category', $category->id) }}" class="category-pill {{ request()->route('category') && request()->route('category')->id == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Products Counter -->
        <div class="products-header">
            <h2 class="section-title">
                <span class="title-icon">🔥</span>
                Produtos em Destaque
            </h2>
            <span class="products-count">{{ $products->count() }} produtos encontrados</span>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid-modern" id="productsGrid">
                @foreach($products as $product)
                    <div class="product-card-modern" data-name="{{ strtolower($product->name) }}" data-price="{{ $product->price }}">
                        <div class="product-image-wrapper">
                            <a href="{{ route('shop.show', $product->uuid) }}">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img" loading="lazy">
                            </a>
                            @if($product->created_at->diffInDays(now()) < 7)
                                <span class="product-badge new">Novo</span>
                            @endif
                            <div class="product-actions">
                                <button class="action-btn wishlist-btn" title="Adicionar aos favoritos">
                                    <i class="far fa-heart"></i>
                                </button>
                                <a href="{{ route('shop.show', $product->uuid) }}" class="action-btn view-btn" title="Ver detalhes">
                                    <i class="far fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-content">
                            <span class="product-category-tag">{{ $product->category?->name ?? 'Geral' }}</span>
                            <a href="{{ route('shop.show', $product->uuid) }}">
                                <h3 class="product-title">{{ $product->name }}</h3>
                            </a>
                            <p class="product-desc">{{ Str::limit($product->description, 60) }}</p>
                            <div class="product-bottom">
                                <div class="price-wrapper">
                                    <span class="current-price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                </div>
                                <form action="{{ route('cart.add', $product->uuid) }}" method="POST" class="add-cart-form">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-cart-modern">
                                        <i class="fas fa-shopping-bag"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h2>Nenhum produto encontrado</h2>
                <p>Estamos preparando novidades incríveis para você!</p>
                <a href="{{ route('shop.index') }}" class="btn-primary-modern">
                    <i class="fas fa-home"></i> Voltar ao Início
                </a>
            </div>
        @endif

        <!-- Features Section -->
        <div class="features-section">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Entrega Rápida</h3>
                <p>Receba em até 10 dias úteis</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Compra Segura</h3>
                <p>Seus dados protegidos</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3>Troca Fácil</h3>
                <p>30 dias para trocar</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Suporte 24h</h3>
                <p>Atendimento dedicado</p>
            </div>
        </div>
    </div>

    <style>
        /* Hero Section Moderna */
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
            font-size: 56px;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .gradient-text {
            background: linear-gradient(135deg, #ffd89b 0%, #f9f871 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-modern p {
            font-size: 20px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 24px 48px;
            border-radius: 16px;
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 28px;
            font-weight: 800;
            color: white;
        }

        .stat-label {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255,255,255,0.3);
        }

        /* Main Content */
        .main-content {
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            padding: 20px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #f3f4f6;
            border-radius: 12px;
            padding: 12px 20px;
            flex: 1;
            max-width: 400px;
        }

        .search-box i {
            color: #9ca3af;
            margin-right: 12px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 15px;
            color: #1f2937;
        }

        .search-box input::placeholder {
            color: #9ca3af;
        }

        .filter-options select {
            padding: 12px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
            background: white;
            cursor: pointer;
            outline: none;
            transition: all 0.3s;
        }

        .filter-options select:focus {
            border-color: #667eea;
        }

        /* Categories Modern */
        .categories-modern {
            display: flex;
            gap: 12px;
            margin-bottom: 40px;
            overflow-x: auto;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
        }

        .categories-modern::-webkit-scrollbar {
            display: none;
        }

        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            color: #4b5563;
            white-space: nowrap;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .category-pill:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .category-pill.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Products Header */
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .title-icon {
            font-size: 28px;
        }

        .products-count {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        /* Products Grid Modern */
        .products-grid-modern {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
        }

        /* Product Card Modern */
        .product-card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
        }

        .product-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
        }

        .product-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card-modern:hover .product-img {
            transform: scale(1.08);
        }

        .product-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .product-badge.new {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .product-badge.sale {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .product-actions {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .product-card-modern:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .action-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #4b5563;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            text-decoration: none;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.1);
        }

        .product-content {
            padding: 24px;
        }

        .product-category-tag {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            transition: color 0.3s;
            text-decoration: none;
        }

        .product-title:hover {
            color: #667eea;
        }

        .product-desc {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .product-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-wrapper {
            display: flex;
            flex-direction: column;
        }

        .current-price {
            font-size: 24px;
            font-weight: 800;
            color: #1f2937;
        }

        .old-price {
            font-size: 14px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .add-cart-form {
            margin: 0;
        }

        .btn-cart-modern {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
        }

        .btn-cart-modern:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
        }

        /* Empty State Modern */
        .empty-state-modern {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 80px;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .empty-icon i {
            font-size: 40px;
            color: #9ca3af;
        }

        .empty-state-modern h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .empty-state-modern p {
            color: #6b7280;
            margin-bottom: 24px;
        }

        .btn-primary-modern {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        /* Features Section */
        .features-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 60px;
        }

        .feature-card {
            background: white;
            padding: 32px 24px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .feature-icon i {
            font-size: 28px;
            color: white;
        }

        .feature-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 14px;
            color: #6b7280;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-modern {
                padding: 80px 20px 100px;
            }

            .hero-modern h1 {
                font-size: 42px;
            }

            .hero-stats {
                padding: 20px 30px;
                gap: 25px;
            }

            .stat-number {
                font-size: 24px;
            }

            .products-grid-modern {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .hero-modern {
                padding: 60px 20px 80px;
            }

            .hero-modern h1 {
                font-size: 32px;
            }

            .hero-modern p {
                font-size: 16px;
            }

            .hero-stats {
                flex-direction: row;
                padding: 16px 24px;
                gap: 20px;
            }

            .stat-divider {
                height: 30px;
            }

            .stat-number {
                font-size: 20px;
            }

            .stat-label {
                font-size: 11px;
            }

            .filter-bar {
                flex-direction: column;
                padding: 16px;
            }

            .search-box {
                max-width: 100%;
            }

            .filter-options {
                width: 100%;
            }

            .filter-options select {
                width: 100%;
            }

            .products-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .section-title {
                font-size: 22px;
            }

            .products-grid-modern {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .product-img {
                height: 200px;
            }

            .product-content {
                padding: 16px;
            }

            .product-title {
                font-size: 15px;
            }

            .product-desc {
                font-size: 13px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .current-price {
                font-size: 18px;
            }

            .btn-cart-modern {
                width: 44px;
                height: 44px;
                font-size: 16px;
            }

            .features-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .feature-card {
                padding: 24px 16px;
            }

            .feature-icon {
                width: 56px;
                height: 56px;
            }

            .feature-icon i {
                font-size: 22px;
            }

            .feature-card h3 {
                font-size: 14px;
            }

            .feature-card p {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .hero-modern h1 {
                font-size: 28px;
            }

            .hero-badge {
                font-size: 12px;
                padding: 8px 16px;
            }

            .hero-stats {
                gap: 15px;
            }

            .products-grid-modern {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .product-img {
                height: 160px;
            }

            .product-content {
                padding: 12px;
            }

            .product-category-tag {
                font-size: 10px;
            }

            .product-title {
                font-size: 14px;
            }

            .product-desc {
                display: none;
            }

            .current-price {
                font-size: 16px;
            }

            .btn-cart-modern {
                width: 40px;
                height: 40px;
                font-size: 14px;
                border-radius: 10px;
            }

            .category-pill {
                padding: 10px 16px;
                font-size: 13px;
            }
        }
    </style>

    <script>
        function filterProducts() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const products = document.querySelectorAll('.product-card-modern');
            
            products.forEach(product => {
                const name = product.getAttribute('data-name');
                if (name.includes(searchValue)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        function sortProducts() {
            const sortValue = document.getElementById('sortSelect').value;
            const grid = document.getElementById('productsGrid');
            const products = Array.from(grid.querySelectorAll('.product-card-modern'));
            
            products.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                const nameA = a.getAttribute('data-name');
                const nameB = b.getAttribute('data-name');
                
                switch(sortValue) {
                    case 'price-low':
                        return priceA - priceB;
                    case 'price-high':
                        return priceB - priceA;
                    case 'name':
                        return nameA.localeCompare(nameB);
                    default:
                        return 0;
                }
            });
            
            products.forEach(product => grid.appendChild(product));
        }
    </script>
@endsection

