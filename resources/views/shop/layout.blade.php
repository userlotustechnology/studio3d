<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Loja Online') - Minha Loja</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            :root {
                --primary-color: #2563eb;
                --secondary-color: #f59e0b;
                --text-dark: #1f2937;
                --text-light: #6b7280;
                --bg-light: #f9fafb;
                --border-color: #e5e7eb;
            }

            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                line-height: 1.6;
                color: var(--text-dark);
                background-color: var(--bg-light);
            }

            a {
                text-decoration: none;
                color: inherit;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            /* Header */
            header {
                background-color: white;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 0;
            }

            .logo {
                font-size: 28px;
                font-weight: 600;
                color: var(--primary-color);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .nav-links {
                display: flex;
                gap: 30px;
                align-items: center;
            }

            .nav-links a {
                color: var(--text-dark);
                transition: color 0.3s;
            }

            .nav-links a:hover {
                color: var(--primary-color);
            }

            .auth-buttons {
                display: flex;
                gap: 15px;
            }

            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 500;
                transition: all 0.3s;
                font-family: inherit;
                font-size: 14px;
            }

            .btn-primary {
                background-color: var(--primary-color);
                color: white;
            }

            .btn-primary:hover {
                background-color: #1d4ed8;
            }

            .btn-secondary {
                background-color: transparent;
                color: var(--primary-color);
                border: 1px solid var(--primary-color);
            }

            .btn-secondary:hover {
                background-color: var(--primary-color);
                color: white;
            }

            /* Hero Section */
            .hero {
                background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
                color: white;
                padding: 80px 20px;
                text-align: center;
                margin-bottom: 60px;
            }

            .hero h1 {
                font-size: 48px;
                margin-bottom: 20px;
                font-weight: 600;
            }

            .hero p {
                font-size: 20px;
                opacity: 0.9;
                margin-bottom: 30px;
            }

            /* Categories */
            .categories {
                display: flex;
                gap: 15px;
                margin-bottom: 40px;
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .category-btn {
                padding: 10px 20px;
                border: 2px solid var(--border-color);
                background-color: white;
                border-radius: 25px;
                cursor: pointer;
                transition: all 0.3s;
                white-space: nowrap;
                font-weight: 500;
            }

            .category-btn:hover,
            .category-btn.active {
                background-color: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            /* Products Grid */
            .products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 30px;
                margin-bottom: 60px;
            }

            .product-card {
                background-color: white;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                transition: all 0.3s;
                cursor: pointer;
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            }

            .product-image {
                width: 100%;
                height: 250px;
                object-fit: cover;
                background-color: var(--bg-light);
            }

            .product-info {
                padding: 20px;
            }

            .product-category {
                font-size: 12px;
                color: var(--primary-color);
                font-weight: 600;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .product-name {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 8px;
                color: var(--text-dark);
            }

            .product-description {
                font-size: 14px;
                color: var(--text-light);
                margin-bottom: 15px;
                line-height: 1.4;
            }

            .product-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .product-price {
                font-size: 24px;
                font-weight: 700;
                color: var(--primary-color);
            }

            .btn-add-cart {
                background-color: var(--secondary-color);
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.3s;
                font-weight: 500;
            }

            .btn-add-cart:hover {
                background-color: #d97706;
            }

            /* Footer */
            footer {
                background-color: var(--text-dark);
                color: white;
                padding: 40px 20px;
                margin-top: 80px;
            }

            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 40px;
                margin-bottom: 30px;
            }

            .footer-section h3 {
                margin-bottom: 15px;
                font-size: 16px;
            }

            .footer-section ul {
                list-style: none;
            }

            .footer-section ul li {
                margin-bottom: 10px;
            }

            .footer-section a {
                color: #d1d5db;
                transition: color 0.3s;
            }

            .footer-section a:hover {
                color: white;
            }

            .footer-bottom {
                border-top: 1px solid #374151;
                padding-top: 20px;
                text-align: center;
                color: #d1d5db;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 60px 20px;
            }

            .empty-state i {
                font-size: 48px;
                color: var(--border-color);
                margin-bottom: 20px;
            }

            .empty-state h2 {
                color: var(--text-dark);
                margin-bottom: 10px;
            }

            .empty-state p {
                color: var(--text-light);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 32px;
                }

                .hero p {
                    font-size: 16px;
                }

                .products-grid {
                    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                    gap: 20px;
                }

                .nav-links {
                    display: none;
                }
            }
        </style>
    @endif
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <a href="{{ route('shop.index') }}" class="logo">
                    <i class="fas fa-store"></i>
                    Minha Loja
                </a>
                <nav class="nav-links">
                    <a href="{{ route('shop.index') }}">Produtos</a>
                    <a href="#about">Sobre</a>
                    <a href="#contact">Contato</a>
                    <a href="{{ route('orders.search-form') }}" style="color: var(--primary-color); font-weight: 600;">
                        <i class="fas fa-search"></i> Consultar Pedido
                    </a>
                </nav>
                <div class="auth-buttons" style="gap: 20px;">
                    <a href="{{ route('cart.index') }}" style="position: relative; color: black; font-weight: 600;">
                        <i class="fas fa-shopping-cart" style="font-size: 20px;"></i>
                        <span id="cart-count" style="position: absolute; top: -8px; right: -8px; background-color: var(--secondary-color); color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">0</span>
                    </a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sobre Nós</h3>
                    <p>Bem-vindo à nossa loja online! Oferecemos produtos de qualidade com os melhores preços do mercado.</p>
                </div>
                <div class="footer-section">
                    <h3>Navegação</h3>
                    <ul>
                        <li><a href="{{ route('shop.index') }}">Produtos</a></li>
                        <li><a href="#about">Sobre</a></li>
                        <li><a href="#contact">Contato</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <ul>
                        <li><i class="fas fa-envelope"></i> contato@minaloja.com</li>
                        <li><i class="fas fa-phone"></i> (11) 98765-4321</li>
                        <li><i class="fas fa-map-marker-alt"></i> São Paulo, SP</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Redes Sociais</h3>
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Minha Loja. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Atualizar contador de carrinho via servidor
        function updateCartCount() {
            fetch('/carrinho/count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count || 0;
                }
            })
            .catch(error => console.error('Erro ao atualizar contador:', error));
        }

        // Adicionar ao carrinho via POST
        function addToCart(productId, productName, productPrice, quantity = 1) {
            try {
                console.log('addToCart called with:', { productId, productName, productPrice, quantity });
                
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
                } else {
                    console.error('CSRF token not found');
                    alert('Erro de segurança: token CSRF não encontrado');
                    return false;
                }
                
                // Quantity
                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = 'quantity';
                quantityInput.value = quantity;
                form.appendChild(quantityInput);
                
                // Adicionar o formulário ao body se ainda não estiver lá
                if (!form.parentElement) {
                    document.body.appendChild(form);
                }
                
                console.log('Submitting form to:', form.action);
                form.submit();
                
                // Atualizar contador após adicionar
                setTimeout(updateCartCount, 500);
            } catch (error) {
                console.error('Erro ao adicionar ao carrinho:', error);
                alert('Erro ao processar sua solicitação. Por favor, tente novamente.');
                return false;
            }
        }

        // Chamar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            
            // Handler para botões de adicionar ao carrinho com data-attributes
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-add-cart');
                if (btn) {
                    e.preventDefault();
                    const productId = btn.dataset.productId;
                    const productName = btn.dataset.productName;
                    const productPrice = btn.dataset.productPrice;
                    if (productId && productName && productPrice) {
                        addToCart(productId, productName, productPrice);
                    }
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
