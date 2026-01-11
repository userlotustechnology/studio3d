@extends('shop.layout')

@section('title', $product->name . ' - Loja Online')

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
                @if($product->created_at->diffInDays(now()) < 7)
                    ✨ Novo Lançamento
                @else
                    📦 Detalhes do Produto
                @endif
            </span>
            <h1>{{ $product->name }}</h1>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-modern">
        <div class="container">
            <a href="{{ route('shop.index') }}"><i class="fas fa-home"></i> Produtos</a>
            <span>/</span>
            @if($product->category_id && $product->category)
                <a href="{{ route('shop.category', $product->category->id) }}">{{ $product->category->name }}</a>
            @else
                <span>Sem categoria</span>
            @endif
            <span>/</span>
            <span>{{ $product->name }}</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Product Details Grid -->
        <div class="product-detail-grid">
            <!-- Product Image Gallery -->
            <div class="product-image-section">
                <div class="main-image-wrapper">
                    <img id="mainProductImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="main-product-image">
                    @if($product->created_at->diffInDays(now()) < 7)
                        <span class="product-badge new">Novo</span>
                    @endif
                </div>
                
                <!-- Thumbnail Gallery -->
                @if($product->images->count() > 1)
                    <div class="product-thumbnails-modern">
                        @foreach($product->images as $index => $image)
                            <img src="{{ $image->image_url }}" 
                                alt="{{ $product->name }} - {{ $index + 1 }}" 
                                class="product-thumbnail-modern {{ $image->is_main ? 'active' : '' }}"
                                onclick="changeMainImage(this, '{{ $image->image_url }}')">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="product-info-section">
                <div class="product-header-info">
                    <span class="product-category-badge">{{ $product->category?->name ?? 'Geral' }}</span>
                    <h1 class="product-title-modern">{{ $product->name }}</h1>
                </div>

                <!-- Price Section -->
                <div class="price-section-modern">
                    <span class="price-label">Preço:</span>
                    <span class="price-value">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                </div>

                <!-- Stock Status -->
                <div class="stock-status-modern">
                    @if($product->stock > 0)
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $product->stock }} unidades em estoque</span>
                    @else
                        <i class="fas fa-times-circle"></i>
                        <span>Fora de estoque</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="product-description-modern">
                    <p>{{ $product->description }}</p>
                </div>

                <!-- Meta Information -->
                <div class="product-meta-modern">
                    <div class="meta-item">
                        <span class="meta-label">Categoria:</span>
                        <span class="meta-value">{{ $product->category?->name ?? 'Sem categoria' }}</span>
                    </div>
                    @if($product->sku)
                        <div class="meta-item">
                            <span class="meta-label">SKU:</span>
                            <span class="meta-value">{{ $product->sku }}</span>
                        </div>
                    @endif
                    <div class="meta-item">
                        <span class="meta-label">Tipo:</span>
                        <span class="meta-value">
                            @if($product->type === 'digital')
                                <i class="fas fa-download"></i> Digital
                            @else
                                <i class="fas fa-box"></i> Físico
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="purchase-section">
                    <div class="quantity-selector">
                        <label for="quantity">Quantidade:</label>
                        <div class="quantity-input">
                            <button type="button" class="qty-btn" onclick="decrementQuantity()">−</button>
                            <input type="number" id="quantity" value="1" min="1" readonly>
                            <button type="button" class="qty-btn" onclick="incrementQuantity()">+</button>
                        </div>
                    </div>

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="quantity" id="cartQuantity" value="1">
                        <button type="submit" class="btn-cart-checkout" @if($product->stock <= 0) disabled @endif>
                            <i class="fas fa-shopping-bag"></i> Adicionar ao Carrinho
                        </button>
                    </form>
                </div>

                <!-- Features -->
                <div class="product-features-modern">
                    <div class="feature-item">
                        <i class="fas fa-truck"></i>
                        <span>Entrega em até 3 dias úteis</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Compra 100% segura</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo"></i>
                        <span>30 dias para trocar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="tabs-section-modern">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab('specifications', this)">
                    <i class="fas fa-list"></i> Especificações
                </button>
                <button class="tab-btn" onclick="switchTab('reviews', this)">
                    <i class="fas fa-star"></i> Avaliações
                </button>
            </div>

            <div id="specifications-content" class="tab-content-modern" style="display: block;">
                <table class="specs-table">
                    <tr>
                        <td>Categoria</td>
                        <td>{{ $product->category?->name ?? 'Sem categoria' }}</td>
                    </tr>
                    <tr>
                        <td>Preço</td>
                        <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                    </tr>
                    @if($product->sku)
                    <tr>
                        <td>SKU</td>
                        <td>{{ $product->sku }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Disponibilidade</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge-success">✓ Em Estoque ({{ $product->stock }})</span>
                            @else
                                <span class="badge-danger">✗ Fora de Estoque</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Data de Criação</td>
                        <td>{{ $product->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>

            <div id="reviews-content" class="tab-content-modern" style="display: none;">
                <div class="empty-reviews">
                    <i class="fas fa-star"></i>
                    <h3>Nenhuma avaliação ainda</h3>
                    <p>Seja o primeiro a avaliar este produto!</p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="related-products-section">
                <h2 class="section-title">
                    <span class="title-icon">🔗</span>
                    Produtos Relacionados
                </h2>
                <div class="products-grid-modern" id="relatedGrid">
                    @foreach($relatedProducts as $related)
                        <div class="product-card-modern" data-name="{{ strtolower($related->name) }}" data-price="{{ $related->price }}">
                            <div class="product-image-wrapper">
                                <a href="{{ route('shop.show', $related->id) }}">
                                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="product-img" loading="lazy">
                                </a>
                                @if($related->created_at->diffInDays(now()) < 7)
                                    <span class="product-badge new">Novo</span>
                                @endif
                                <div class="product-actions">
                                    <a href="{{ route('shop.show', $related->id) }}" class="action-btn view-btn" title="Ver detalhes">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="product-content">
                                <span class="product-category-tag">{{ $related->category?->name ?? 'Geral' }}</span>
                                <a href="{{ route('shop.show', $related->id) }}">
                                    <h3 class="product-title">{{ $related->name }}</h3>
                                </a>
                                <p class="product-desc">{{ Str::limit($related->description, 60) }}</p>
                                <div class="product-bottom">
                                    <div class="price-wrapper">
                                        <span class="current-price">R$ {{ number_format($related->price, 2, ',', '.') }}</span>
                                    </div>
                                    <form action="{{ route('cart.add', $related->id) }}" method="POST" class="add-cart-form">
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
            </div>
        @endif
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
            font-size: 48px;
            font-weight: 800;
            color: white;
            margin-bottom: 0;
            line-height: 1.2;
        }

        /* Breadcrumb Modern */
        .breadcrumb-modern {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .breadcrumb-modern .container {
            font-size: 13px;
            color: #6b7280;
        }

        .breadcrumb-modern a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-modern a:hover {
            color: #764ba2;
        }

        .breadcrumb-modern span {
            margin: 0 8px;
            color: #d1d5db;
        }

        /* Main Content */
        .main-content {
            padding: 60px 20px;
        }

        /* Product Details Grid */
        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 80px;
        }

        /* Product Image Section */
        .product-image-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .main-image-wrapper {
            position: relative;
            background: #f9fafb;
            border-radius: 20px;
            overflow: hidden;
        }

        .main-product-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            transition: transform 0.5s ease;
            cursor: zoom-in;
        }

        .main-image-wrapper:hover .main-product-image {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .product-badge.new {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .product-thumbnails-modern {
            display: flex;
            gap: 12px;
            overflow-x: auto;
        }

        .product-thumbnail-modern {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .product-thumbnail-modern:hover,
        .product-thumbnail-modern.active {
            border-color: #667eea;
            transform: scale(1.05);
        }

        /* Product Info Section */
        .product-info-section {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .product-header-info {
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .product-category-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667eea;
            margin-bottom: 12px;
        }

        .product-title-modern {
            font-size: 32px;
            font-weight: 800;
            color: #1f2937;
            margin: 0;
        }

        /* Price Section */
        .price-section-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 16px;
        }

        .price-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
        }

        .price-value {
            font-size: 32px;
            font-weight: 800;
            color: #667eea;
        }

        /* Stock Status */
        .stock-status-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            border-radius: 8px;
            font-weight: 600;
            color: #059669;
        }

        .stock-status-modern i {
            font-size: 18px;
        }

        /* Product Description */
        .product-description-modern {
            padding: 20px 0;
            border-bottom: 2px solid #e5e7eb;
        }

        .product-description-modern p {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.8;
            margin: 0;
        }

        /* Product Meta */
        .product-meta-modern {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px 0;
            border-bottom: 2px solid #e5e7eb;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .meta-label {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 700;
            text-transform: uppercase;
        }

        .meta-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
        }

        /* Purchase Section */
        .purchase-section {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 2px solid #e5e7eb;
            align-items: flex-end;
        }

        .quantity-selector {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quantity-selector label {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .quantity-input {
            display: flex;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            width: fit-content;
        }

        .qty-btn {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 18px;
            color: #667eea;
            transition: all 0.3s;
        }

        .qty-btn:hover {
            background: #f3f4f6;
        }

        .quantity-input input {
            width: 60px;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        .add-to-cart-form {
            flex: 1;
        }

        .btn-cart-checkout {
            width: 100%;
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-cart-checkout:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
        }

        .btn-cart-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Product Features */
        .product-features-modern {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            padding-top: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 8px;
            font-size: 14px;
            color: #4b5563;
        }

        .feature-item i {
            font-size: 18px;
            color: #667eea;
            min-width: 24px;
        }

        /* Tabs Section */
        .tabs-section-modern {
            padding: 40px 0;
            margin-bottom: 60px;
        }

        .tabs-header {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 30px;
        }

        .tab-btn {
            padding: 16px 24px;
            background: none;
            border: none;
            font-size: 15px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            position: relative;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn.active {
            color: #667eea;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #667eea;
        }

        .tab-content-modern {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .specs-table {
            width: 100%;
            border-collapse: collapse;
        }

        .specs-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .specs-table td {
            padding: 16px;
            font-size: 14px;
        }

        .specs-table td:first-child {
            font-weight: 700;
            color: #1f2937;
            width: 30%;
            background: #f9fafb;
        }

        .specs-table td:last-child {
            color: #6b7280;
        }

        .badge-success {
            color: #059669;
            font-weight: 600;
        }

        .badge-danger {
            color: #dc2626;
            font-weight: 600;
        }

        .empty-reviews {
            text-align: center;
            padding: 60px 20px;
            background: #f9fafb;
            border-radius: 16px;
        }

        .empty-reviews i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 20px;
            display: block;
        }

        .empty-reviews h3 {
            font-size: 20px;
            color: #1f2937;
            margin: 0 0 10px 0;
        }

        .empty-reviews p {
            color: #6b7280;
            margin: 0;
        }

        /* Related Products Section */
        .related-products-section {
            padding-top: 60px;
            border-top: 2px solid #e5e7eb;
            margin-top: 80px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .title-icon {
            font-size: 28px;
        }

        /* Products Grid Modern */
        .products-grid-modern {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
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
            display: block;
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

        /* Responsive */
        @media (max-width: 992px) {
            .hero-modern {
                padding: 80px 20px 100px;
            }

            .hero-modern h1 {
                font-size: 42px;
            }

            .product-detail-grid {
                gap: 40px;
            }

            .main-product-image {
                height: 400px;
            }

            .product-title-modern {
                font-size: 28px;
            }

            .price-value {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .hero-modern {
                padding: 60px 20px 80px;
            }

            .hero-modern h1 {
                font-size: 32px;
            }

            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .main-product-image {
                height: 350px;
            }

            .product-thumbnail-modern {
                width: 80px;
                height: 80px;
            }

            .product-title-modern {
                font-size: 24px;
            }

            .price-value {
                font-size: 24px;
            }

            .purchase-section {
                flex-direction: column;
                align-items: stretch;
            }

            .quantity-input {
                margin-bottom: 0;
            }

            .btn-cart-checkout {
                min-height: 50px;
            }

            .product-meta-modern {
                grid-template-columns: 1fr;
            }

            .tabs-header {
                flex-wrap: wrap;
            }

            .tab-btn {
                padding: 12px 16px;
                font-size: 14px;
            }

            .products-grid-modern {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-modern h1 {
                font-size: 24px;
            }

            .breadcrumb-modern {
                padding: 12px 0;
            }

            .breadcrumb-modern .container {
                font-size: 11px;
            }

            .main-content {
                padding: 30px 15px;
            }

            .main-product-image {
                height: 280px;
            }

            .product-thumbnail-modern {
                width: 70px;
                height: 70px;
            }

            .product-title-modern {
                font-size: 20px;
            }

            .price-value {
                font-size: 20px;
            }

            .specs-table td {
                padding: 12px 8px;
                font-size: 13px;
            }

            .product-features-modern {
                gap: 10px;
            }

            .feature-item {
                padding: 10px;
                font-size: 13px;
            }

            .products-grid-modern {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }
    </style>

    <script>
        function incrementQuantity() {
            const input = document.getElementById('quantity');
            const cartInput = document.getElementById('cartQuantity');
            input.value = parseInt(input.value) + 1;
            cartInput.value = input.value;
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            const cartInput = document.getElementById('cartQuantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                cartInput.value = input.value;
            }
        }

        function changeMainImage(thumbnail, imageUrl) {
            document.getElementById('mainProductImage').src = imageUrl;
            document.querySelectorAll('.product-thumbnail-modern').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }

        function switchTab(tabName, button) {
            document.querySelectorAll('.tab-content-modern').forEach(tab => {
                tab.style.display = 'none';
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById(tabName + '-content').style.display = 'block';
            button.classList.add('active');
        }
    </script>
@endsection
