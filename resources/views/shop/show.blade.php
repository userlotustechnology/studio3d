@extends('shop.layout')

@section('title', $product->name . ' - Loja Online')

@section('content')
    <!-- Breadcrumb -->
    <div class="container" style="padding: 30px 0; border-bottom: 1px solid var(--border-color);">
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
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-bottom: 60px;">
            <!-- Product Image -->
            <div>
                <div style="margin-bottom:12px;">
                    <img id="main-product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; border-radius: 8px; object-fit: cover; max-height: 500px;">
                </div>

                @if($product->images->count() > 0)
                <div style="display:flex; gap:8px; margin-top:12px;">
                    @foreach($product->images as $img)
                        <img src="{{ $img->image_url }}" alt="{{ $product->name }}" style="width:80px; height:80px; object-fit: cover; border-radius:6px; cursor:pointer;" onclick="document.getElementById('main-product-image').src='{{ $img->image_url }}'">
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Information -->
            <div>
                <div class="product-category" style="margin-bottom: 15px;">{{ $product->category?->name ?? 'Sem categoria' }}</div>
                
                <h1 style="font-size: 36px; margin-bottom: 20px; color: var(--text-dark);">{{ $product->name }}</h1>
                
                <div style="font-size: 48px; font-weight: 700; color: var(--primary-color); margin-bottom: 30px;">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; font-size: 14px;">
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

                <p style="font-size: 16px; color: var(--text-light); line-height: 1.8; margin-bottom: 30px;">
                    {{ $product->description }}
                </p>

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

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px;">
                    <button class="btn btn-primary" onclick="addToCartWithQuantity({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" style="width: 100%; padding: 15px; font-size: 16px;">
                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                    </button>
                    <button style="width: 100%; padding: 15px; font-size: 16px; border: 2px solid var(--primary-color); background-color: white; color: var(--primary-color); border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s;">
                        <i class="fas fa-heart"></i> Favoritar
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; padding-top: 20px;">
                    <div style="text-align: center;">
                        <i class="fas fa-truck" style="font-size: 28px; color: var(--primary-color); margin-bottom: 10px;"></i>
                        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 5px;">Frete Rápido</h4>
                        <p style="font-size: 12px; color: var(--text-light);">Entrega em até 10 dias úteis</p>
                    </div>
                    <div style="text-align: center;">
                        <i class="fas fa-shield-alt" style="font-size: 28px; color: var(--primary-color); margin-bottom: 10px;"></i>
                        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 5px;">Seguro</h4>
                        <p style="font-size: 12px; color: var(--text-light);">Compra protegida</p>
                    </div>
                    <div style="text-align: center;">
                        <i class="fas fa-undo-alt" style="font-size: 28px; color: var(--primary-color); margin-bottom: 10px;"></i>
                        <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 5px;">Troca Fácil</h4>
                        <p style="font-size: 12px; color: var(--text-light);">30 dias para devolver</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div style="margin-bottom: 60px;">
            <div style="border-bottom: 2px solid var(--border-color); margin-bottom: 30px;">
                <button onclick="switchTab('description')" class="tab-button active" style="padding: 15px 30px; font-size: 16px; font-weight: 600; border: none; background: none; cursor: pointer; position: relative; color: var(--text-dark);">
                    Descrição
                    <span style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background-color: var(--primary-color);"></span>
                </button>
                <button onclick="switchTab('specifications')" class="tab-button" style="padding: 15px 30px; font-size: 16px; font-weight: 600; border: none; background: none; cursor: pointer; position: relative; color: var(--text-light);">
                    Especificações
                </button>
                <button onclick="switchTab('reviews')" class="tab-button" style="padding: 15px 30px; font-size: 16px; font-weight: 600; border: none; background: none; cursor: pointer; position: relative; color: var(--text-light);">
                    Avaliações
                </button>
            </div>

            <div id="description-content" class="tab-content" style="display: block;">
                <p style="font-size: 16px; color: var(--text-light); line-height: 1.8;">
                    {{ $product->description }}
                </p>
                <ul style="margin-top: 20px; margin-left: 20px; color: var(--text-light);">
                    <li style="margin-bottom: 10px;">Produto de alta qualidade e durabilidade</li>
                    <li style="margin-bottom: 10px;">Certificado e testado antes do envio</li>
                    <li style="margin-bottom: 10px;">Embalagem segura e profissional</li>
                    <li style="margin-bottom: 10px;">Suporte técnico disponível</li>
                </ul>
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
                                        <button type="button" class="btn-add-cart" onclick="addToCart({{ $related->id }}, {{ json_encode($related->name) }}, {{ $related->price }}); return false;">
                                            <i class="fas fa-shopping-cart"></i> Comprar
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function incrementQuantity() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1;
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function addToCartWithQuantity(productId, productName, productPrice) {
            const quantity = parseInt(document.getElementById('quantity').value);
            
            // Criar um form temporário e enviar via POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/carrinho/adicionar/${productId}`;
            
            // Token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.content;
                form.appendChild(csrfInput);
            }
            
            // Quantity
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = quantity;
            form.appendChild(quantityInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.style.color = 'var(--text-light)';
                btn.style.borderBottom = 'none';
            });

            // Show selected tab
            document.getElementById(tabName + '-content').style.display = 'block';

            // Add active class to clicked button
            event.target.style.color = 'var(--text-dark)';
            event.target.style.borderBottomWidth = '2px';
            event.target.style.borderBottomColor = 'var(--primary-color)';
        }
    </script>
@endsection
