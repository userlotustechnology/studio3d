@extends('shop.layout')

@section('title', $product->name . ' - Loja Online')

@section('content')
    <!-- Breadcrumb -->
    <div class="container breadcrumb-container" style="padding: 30px 0; border-bottom: 1px solid var(--border-color);">
        <nav style="font-size: 14px; color: var(--text-light);">
            <a href="{{ route('shop.index') }}" style="color: var(--primary-color);">Produtos</a>
            <span> / </span>
            @if($product->category_id && $product->category)
            <a href="{{ route('shop.category', $product->category->id) }}" style="color: var(--primary-color);">{{ $product->category->name }}</a>
            @else
            <span style="color: var(--text-light);">Sem categoria</span>
            @endif
            <span> / </span>
            <span>{{ $product->name }}</span>
        </nav>
    </div>

    <!-- Product Details -->
    <div class="container" style="padding: 60px 20px;">
        <div class="product-detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-bottom: 60px;">
            <!-- Product Image Gallery -->
            <div class="product-image-container">
                <!-- Main Image -->
                <div style="margin-bottom: 15px;">
                    <img id="mainProductImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                        style="width: 100%; border-radius: 8px; object-fit: cover; max-height: 500px; cursor: zoom-in;"
                        onclick="openImageModal(this.src)">
                </div>
                
                <!-- Thumbnail Gallery -->
                @if($product->images->count() > 1)
                <div class="product-thumbnails" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach($product->images as $index => $image)
                    <div class="thumbnail-wrapper" style="width: 80px; cursor: pointer;">
                        <img src="{{ $image->image_url }}" 
                            alt="{{ $product->name }} - Imagem {{ $index + 1 }}" 
                            class="product-thumbnail {{ $image->is_main ? 'active' : '' }}"
                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 2px solid {{ $image->is_main ? 'var(--primary-color)' : 'transparent' }}; transition: all 0.3s;"
                            onclick="changeMainImage(this, '{{ $image->image_url }}')">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="product-detail-info">
                <div class="product-category" style="margin-bottom: 15px;">{{ $product->category?->name ?? 'Sem categoria' }}</div>
                
                <h1 class="product-detail-title" style="font-size: 36px; margin-bottom: 20px; color: var(--text-dark);">{{ $product->name }}</h1>
                
                <div class="product-detail-price" style="font-size: 48px; font-weight: 700; color: var(--primary-color); margin-bottom: 30px;">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </div>

                <div class="product-meta-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; font-size: 14px;">
                    @if($product->sku)
                    <div>
                        <span style="color: var(--text-light);">SKU:</span>
                        <span style="font-weight: 600; color: var(--text-dark);">{{ $product->sku }}</span>
                    </div>
                    @endif
                    <div>
                        <span style="color: var(--text-light);">Tipo:</span>
                        <span style="font-weight: 600; color: var(--text-dark);">
                            @if($product->type === 'digital')
                                <i class="fas fa-download"></i> Digital
                            @else
                                <i class="fas fa-box"></i> Físico
                            @endif
                        </span>
                    </div>
                </div>

                <div style="background-color: #f0f9ff; border-left: 4px solid var(--primary-color); padding: 15px; margin-bottom: 30px; border-radius: 4px;">
                    <p style="color: var(--text-dark); font-weight: 500;">
                        <i class="fas fa-check-circle" style="color: #10b981; margin-right: 10px;"></i>
                        @if($product->stock > 0)
                            Produto em estoque ({{ $product->stock }} unidades)
                        @else
                            <i class="fas fa-times-circle" style="color: #ef4444;"></i> Produto fora de estoque
                        @endif
                    </p>
                </div>

                <div style="border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 20px 0; margin-bottom: 30px;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 10px; color: var(--text-dark);">Quantidade:</label>
                            <div style="display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 4px; width: fit-content;">
                                <button onclick="decrementQuantity()" style="background: none; border: none; padding: 10px 15px; cursor: pointer; font-size: 18px;">−</button>
                                <input type="number" id="quantity" value="1" min="1" style="width: 60px; text-align: center; border: none; font-size: 16px;" readonly>
                                <button onclick="incrementQuantity()" style="background: none; border: none; padding: 10px 15px; cursor: pointer; font-size: 18px;">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-action-grid" style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 30px;">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="quantity" id="cartQuantity" value="1">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">
                            <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div style="margin-bottom: 60px;">
            <div style="border-bottom: 2px solid var(--border-color); margin-bottom: 30px;">
                <button onclick="switchTab('description', this)" class="tab-button active" style="padding: 15px 30px; font-size: 16px; font-weight: 600; border: none; background: none; cursor: pointer; position: relative; color: var(--text-dark);">
                    Descrição
                    <span class="tab-indicator" style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background-color: var(--primary-color);"></span>
                </button>
                <button onclick="switchTab('specifications', this)" class="tab-button" style="padding: 15px 30px; font-size: 16px; font-weight: 600; border: none; background: none; cursor: pointer; position: relative; color: var(--text-light);">
                    Especificações
                    <span class="tab-indicator" style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background-color: var(--primary-color); display: none;"></span>
                </button>
                <button onclick="switchTab('reviews', this)" class="tab-button" style="padding: 15px 30px; font-size: 16px; font-weight: 600; border: none; background: none; cursor: pointer; position: relative; color: var(--text-light);">
                    Avaliações
                    <span class="tab-indicator" style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background-color: var(--primary-color); display: none;"></span>
                </button>
            </div>

            <div id="description-content" class="tab-content" style="display: block;">
                <p style="font-size: 16px; color: var(--text-light); line-height: 1.8;">
                    {{ $product->description }}
                </p>
            </div>

            <div id="specifications-content" class="tab-content" style="display: none;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 12px 0; font-weight: 600; color: var(--text-dark); width: 30%;">Categoria</td>
                        <td style="padding: 12px 0; color: var(--text-light);">{{ $product->category?->name ?? 'Sem categoria' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 12px 0; font-weight: 600; color: var(--text-dark);">Preço</td>
                        <td style="padding: 12px 0; color: var(--text-light);">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 12px 0; font-weight: 600; color: var(--text-dark);">SKU</td>
                        <td style="padding: 12px 0; color: var(--text-light);">PROD-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 12px 0; font-weight: 600; color: var(--text-dark);">Disponibilidade</td>
                        <td style="padding: 12px 0; color: var(--text-light);"><span style="color: #10b981; font-weight: 600;">✓ Em Estoque</span></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; font-weight: 600; color: var(--text-dark);">Data de Criação</td>
                        <td style="padding: 12px 0; color: var(--text-light);">{{ $product->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>

            <div id="reviews-content" class="tab-content" style="display: none;">
                <div style="background-color: var(--bg-light); padding: 30px; border-radius: 8px; text-align: center;">
                    <i class="fas fa-star" style="font-size: 48px; color: var(--secondary-color); margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px;">Ainda não há avaliações</h3>
                    <p style="color: var(--text-light); margin-bottom: 20px;">Seja o primeiro a avaliar este produto!</p>
                    <button class="btn btn-primary">
                        Deixar Avaliação
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div style="margin-top: 60px; padding-top: 40px; border-top: 2px solid var(--border-color);">
                <h2 style="font-size: 28px; margin-bottom: 30px; color: var(--text-dark);">Produtos Relacionados</h2>
                <div class="products-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                    @foreach($relatedProducts as $related)
                        <div class="product-card">
                            <a href="{{ route('shop.show', $related->id) }}" style="display: block; text-decoration: none; color: inherit;">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="product-image">
                                <div class="product-info">
                                    <div class="product-category">{{ $related->category?->name ?? 'Sem categoria' }}</div>
                                    <h3 class="product-name">{{ $related->name }}</h3>
                                    <p class="product-description">{{ Str::limit($related->description, 80) }}</p>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            R$ {{ number_format($related->price, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div style="padding: 0 15px 15px;">
                                <button type="button" class="btn-add-cart" style="width: 100%;" onclick="event.stopPropagation(); addToCart({{ $related->id }}, '{{ addslashes($related->name) }}', {{ $related->price }}); return false;">
                                    <i class="fas fa-shopping-cart"></i> Comprar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

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

        function switchTab(tabName, button) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active class from all buttons and hide indicators
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.style.color = 'var(--text-light)';
                btn.classList.remove('active');
                const indicator = btn.querySelector('.tab-indicator');
                if (indicator) indicator.style.display = 'none';
            });

            // Show selected tab
            document.getElementById(tabName + '-content').style.display = 'block';

            // Add active class to clicked button and show indicator
            button.style.color = 'var(--text-dark)';
            button.classList.add('active');
            const indicator = button.querySelector('.tab-indicator');
            if (indicator) indicator.style.display = 'block';
        }

        // Image Gallery Functions
        function changeMainImage(thumbnail, imageUrl) {
            // Update main image
            document.getElementById('mainProductImage').src = imageUrl;
            
            // Update thumbnail borders
            document.querySelectorAll('.product-thumbnail').forEach(thumb => {
                thumb.style.border = '2px solid transparent';
                thumb.classList.remove('active');
            });
            thumbnail.style.border = '2px solid var(--primary-color)';
            thumbnail.classList.add('active');
        }

        // Image Modal for zoom
        function openImageModal(src) {
            const modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); display:flex; align-items:center; justify-content:center; z-index:10000; cursor:pointer;';
            modal.onclick = function() { this.remove(); };
            
            const img = document.createElement('img');
            img.src = src;
            img.style.cssText = 'max-width:90%; max-height:90%; object-fit:contain; border-radius:8px;';
            
            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '&times;';
            closeBtn.style.cssText = 'position:absolute; top:20px; right:30px; font-size:40px; color:white; background:none; border:none; cursor:pointer;';
            closeBtn.onclick = function(e) { e.stopPropagation(); modal.remove(); };
            
            modal.appendChild(img);
            modal.appendChild(closeBtn);
            document.body.appendChild(modal);
            
            // Close on Escape key
            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    modal.remove();
                    document.removeEventListener('keydown', escHandler);
                }
            });
        }
    </script>

    <style>
        /* Product Gallery Styles */
        .product-thumbnail:hover {
            border-color: var(--primary-color) !important;
            transform: scale(1.05);
        }
        
        .product-thumbnails {
            justify-content: flex-start;
        }
        
        /* Product Detail Page Responsive Styles */
        @media (max-width: 992px) {
            .product-detail-grid {
                gap: 30px !important;
            }
            
            .product-detail-title {
                font-size: 28px !important;
            }
            
            .product-detail-price {
                font-size: 36px !important;
            }
            
            .product-thumbnails {
                gap: 8px !important;
            }
            
            .thumbnail-wrapper {
                width: 70px !important;
            }
            
            .product-thumbnail {
                width: 70px !important;
                height: 70px !important;
            }
        }
        
        @media (max-width: 768px) {
            .breadcrumb-container {
                padding: 20px 0 !important;
            }
            
            .breadcrumb-container nav {
                font-size: 12px !important;
            }
            
            .product-detail-grid {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            
            .product-image-container img#mainProductImage {
                max-height: 350px !important;
            }
            
            .product-thumbnails {
                justify-content: center;
            }
            
            .thumbnail-wrapper {
                width: 60px !important;
            }
            
            .product-thumbnail {
                width: 60px !important;
                height: 60px !important;
            }
            
            .product-detail-title {
                font-size: 24px !important;
            }
            
            .product-detail-price {
                font-size: 32px !important;
                margin-bottom: 20px !important;
            }
            
            .product-meta-grid {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }
            
            .product-action-grid {
                grid-template-columns: 1fr !important;
                gap: 10px !important;
            }
            
            .product-action-grid button {
                padding: 12px !important;
                font-size: 14px !important;
            }
            
            .product-features-grid {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }
            
            .product-features-grid > div {
                display: flex !important;
                align-items: center !important;
                gap: 15px !important;
                text-align: left !important;
                padding: 15px !important;
                background: var(--bg-light);
                border-radius: 8px;
            }
            
            .product-features-grid > div i {
                font-size: 24px !important;
                margin-bottom: 0 !important;
            }
            
            .tab-button {
                padding: 12px 15px !important;
                font-size: 14px !important;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 15px !important;
            }
            
            .product-image-container img#mainProductImage {
                max-height: 280px !important;
            }
            
            .thumbnail-wrapper {
                width: 50px !important;
            }
            
            .product-thumbnail {
                width: 50px !important;
                height: 50px !important;
            }
            
            .product-detail-title {
                font-size: 20px !important;
            }
            
            .product-detail-price {
                font-size: 28px !important;
            }
            
            .tab-button {
                padding: 10px 12px !important;
                font-size: 13px !important;
            }
        }
    </style>
@endsection
