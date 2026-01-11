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

            /* Header Modern */
            .header-modern {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
            }

            /* Logo Modern */
            .logo-modern {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
            }

            .logo-icon {
                width: 45px;
                height: 45px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .logo-icon i {
                font-size: 20px;
                color: white;
            }

            .logo-modern:hover .logo-icon {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(15deg);
            }

            .logo-text {
                display: flex;
                flex-direction: column;
            }

            .logo-name {
                font-size: 20px;
                font-weight: 700;
                color: white;
                line-height: 1.2;
            }

            .logo-tagline {
                font-size: 11px;
                color: rgba(255, 255, 255, 0.8);
                font-weight: 500;
                letter-spacing: 0.5px;
            }

            /* Navigation Modern */
            .nav-modern {
                display: flex;
                gap: 8px;
            }

            .nav-link {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                color: rgba(255, 255, 255, 0.9);
                font-weight: 500;
                font-size: 14px;
                border-radius: 10px;
                transition: all 0.3s ease;
                background: transparent;
            }

            .nav-link:hover,
            .nav-link.active {
                background: rgba(255, 255, 255, 0.2);
                color: white;
            }

            .nav-link i {
                font-size: 14px;
            }

            /* Header Actions */
            .header-actions {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            /* Cart Button Modern */
            .cart-btn-modern {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 18px;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                color: white;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .cart-btn-modern:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: translateY(-2px);
            }

            .cart-icon-wrapper {
                position: relative;
            }

            .cart-icon-wrapper i {
                font-size: 18px;
            }

            .cart-badge {
                position: absolute;
                top: -8px;
                right: -10px;
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                color: white;
                font-size: 11px;
                font-weight: 700;
                min-width: 20px;
                height: 20px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
            }

            .cart-text {
                display: none;
            }

            /* User Button Modern */
            .user-btn-modern {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 16px 8px 8px;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                border-radius: 25px;
                color: white;
                font-weight: 500;
                font-size: 14px;
                transition: all 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .user-btn-modern:hover {
                background: rgba(255, 255, 255, 0.25);
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .user-avatar i {
                font-size: 14px;
                color: white;
            }

            /* Login Button Modern */
            .login-btn-modern {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: white;
                border-radius: 10px;
                color: #667eea;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .login-btn-modern:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            }

            /* Mobile Actions */
            .mobile-actions {
                display: none;
                align-items: center;
                gap: 12px;
            }

            .mobile-cart-btn {
                position: relative;
                width: 44px;
                height: 44px;
                background: rgba(255, 255, 255, 0.15);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }

            .mobile-cart-btn i {
                font-size: 18px;
            }

            .cart-badge-mobile {
                position: absolute;
                top: -5px;
                right: -5px;
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                color: white;
                font-size: 10px;
                font-weight: 700;
                min-width: 18px;
                height: 18px;
                border-radius: 9px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Hamburger Button */
            .hamburger-btn {
                width: 44px;
                height: 44px;
                background: rgba(255, 255, 255, 0.15);
                border: none;
                border-radius: 12px;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 5px;
                padding: 12px;
                transition: all 0.3s ease;
            }

            .hamburger-btn:hover {
                background: rgba(255, 255, 255, 0.25);
            }

            .hamburger-line {
                width: 20px;
                height: 2px;
                background: white;
                border-radius: 1px;
                transition: all 0.3s ease;
            }

            .hamburger-btn.active .hamburger-line:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }

            .hamburger-btn.active .hamburger-line:nth-child(2) {
                opacity: 0;
            }

            .hamburger-btn.active .hamburger-line:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -5px);
            }

            /* Legacy Button Styles */
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

            /* Mobile Menu Toggle */
            .mobile-menu-toggle {
                display: none;
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: var(--text-dark);
                padding: 10px;
            }

            .mobile-menu-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 998;
            }

            .mobile-menu-overlay.active {
                display: block;
            }

            .mobile-menu {
                display: none;
                position: fixed;
                top: 0;
                right: -320px;
                width: 320px;
                height: 100%;
                background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
                z-index: 999;
                transition: right 0.3s ease;
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.2);
                overflow-y: auto;
            }

            .mobile-menu.active {
                right: 0;
            }

            .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .mobile-menu-header .logo-modern {
                color: white;
            }

            .mobile-menu-close {
                background: rgba(255, 255, 255, 0.15);
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 10px;
                font-size: 20px;
                cursor: pointer;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-menu-close:hover {
                background: rgba(255, 255, 255, 0.25);
            }

            .mobile-menu-links {
                padding: 20px;
            }

            .mobile-menu-links a {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 14px 16px;
                margin-bottom: 8px;
                border-radius: 12px;
                color: white;
                font-weight: 500;
                font-size: 15px;
                transition: all 0.3s ease;
            }

            .mobile-menu-links a:hover,
            .mobile-menu-links a.active {
                background: rgba(255, 255, 255, 0.2);
            }

            .mobile-menu-links a i {
                width: 24px;
                text-align: center;
                font-size: 16px;
            }

            .mobile-auth-buttons {
                padding: 20px;
                border-top: 1px solid rgba(255, 255, 255, 0.2);
            }

            .mobile-auth-buttons .btn {
                width: 100%;
                text-align: center;
                background: white;
                color: #667eea;
                font-weight: 600;
                border-radius: 12px;
                padding: 14px;
            }

            .mobile-auth-buttons .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            /* Responsive - Desktop Large */
            @media (max-width: 1200px) {
                .container {
                    padding: 0 15px;
                }
            }

            /* Responsive - Tablet */
            @media (max-width: 992px) {
                .hero {
                    padding: 60px 20px;
                }

                .hero h1 {
                    font-size: 36px;
                }

                .hero p {
                    font-size: 18px;
                }

                .products-grid {
                    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                    gap: 25px;
                }

                .footer-content {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            /* Responsive - Mobile Landscape / Small Tablet */
            @media (max-width: 768px) {
                .mobile-actions {
                    display: flex;
                }

                .nav-modern,
                .header-actions {
                    display: none;
                }

                .mobile-menu {
                    display: block;
                }

                .header-content {
                    padding: 10px 0;
                }

                .logo-name {
                    font-size: 18px;
                }

                .logo-tagline {
                    font-size: 10px;
                }

                .logo-icon {
                    width: 40px;
                    height: 40px;
                }

                .logo-icon i {
                    font-size: 18px;
                }

                .hero {
                    padding: 50px 20px;
                    margin-bottom: 40px;
                }

                .hero h1 {
                    font-size: 28px;
                    margin-bottom: 15px;
                }

                .hero p {
                    font-size: 16px;
                    margin-bottom: 20px;
                }

                .categories {
                    gap: 10px;
                    margin-bottom: 30px;
                    padding-bottom: 10px;
                    -webkit-overflow-scrolling: touch;
                }

                .category-btn {
                    padding: 8px 16px;
                    font-size: 14px;
                }

                .products-grid {
                    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                    gap: 15px;
                    margin-bottom: 40px;
                }

                .product-card {
                    border-radius: 8px;
                }

                .product-image {
                    height: 180px;
                }

                .product-info {
                    padding: 15px;
                }

                .product-category {
                    font-size: 11px;
                }

                .product-name {
                    font-size: 15px;
                    margin-bottom: 6px;
                }

                .product-description {
                    font-size: 13px;
                    margin-bottom: 12px;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }

                .product-footer {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 10px;
                }

                .product-price {
                    font-size: 20px;
                }

                .btn-add-cart {
                    width: 100%;
                    padding: 10px;
                    font-size: 13px;
                }

                footer {
                    padding: 30px 15px;
                    margin-top: 50px;
                }

                .footer-content {
                    grid-template-columns: 1fr;
                    gap: 25px;
                }

                .footer-section h3 {
                    font-size: 15px;
                }

                .footer-bottom {
                    font-size: 13px;
                }

                .empty-state {
                    padding: 40px 15px;
                }

                .empty-state i {
                    font-size: 36px;
                }

                .empty-state h2 {
                    font-size: 20px;
                }
            }

            /* Responsive - Mobile Portrait */
            @media (max-width: 480px) {
                .container {
                    padding: 0 12px;
                }

                .header-content {
                    padding: 12px 0;
                }

                .logo {
                    font-size: 18px;
                    gap: 6px;
                }

                .logo i {
                    font-size: 20px;
                }

                .hero {
                    padding: 40px 15px;
                    margin-bottom: 30px;
                }

                .hero h1 {
                    font-size: 24px;
                    line-height: 1.3;
                }

                .hero p {
                    font-size: 14px;
                }

                .categories {
                    margin-left: -12px;
                    margin-right: -12px;
                    padding-left: 12px;
                    padding-right: 12px;
                }

                .category-btn {
                    padding: 8px 14px;
                    font-size: 13px;
                    border-radius: 20px;
                }

                .products-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 12px;
                }

                .product-image {
                    height: 150px;
                }

                .product-info {
                    padding: 12px;
                }

                .product-name {
                    font-size: 14px;
                }

                .product-description {
                    font-size: 12px;
                    -webkit-line-clamp: 2;
                }

                .product-price {
                    font-size: 18px;
                }

                .btn-add-cart {
                    padding: 8px;
                    font-size: 12px;
                }

                .btn-add-cart i {
                    display: none;
                }

                .btn {
                    padding: 10px 16px;
                    font-size: 13px;
                }

                footer {
                    padding: 25px 12px;
                    margin-top: 40px;
                }

                .footer-section {
                    text-align: center;
                }

                .footer-section ul {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 10px;
                }

                .footer-section ul li {
                    margin-bottom: 5px;
                }
            }

            /* Responsive - Extra Small Mobile */
            @media (max-width: 360px) {
                .logo-name {
                    font-size: 16px;
                }

                .logo-tagline {
                    display: none;
                }

                .logo-icon {
                    width: 36px;
                    height: 36px;
                }

                .hero h1 {
                    font-size: 22px;
                }

                .products-grid {
                    gap: 10px;
                }

                .product-info {
                    padding: 10px;
                }

                .product-price {
                    font-size: 16px;
                }

                .mobile-cart-btn,
                .hamburger-btn {
                    width: 40px;
                    height: 40px;
                }
            }

            /* Touch-friendly styles for mobile */
            @media (hover: none) and (pointer: coarse) {
                .product-card:hover {
                    transform: none;
                }

                .btn:hover,
                .btn-add-cart:hover,
                .category-btn:hover {
                    opacity: 0.9;
                }
            }

            /* Landscape orientation adjustments */
            @media (max-height: 500px) and (orientation: landscape) {
                .hero {
                    padding: 30px 20px;
                    margin-bottom: 30px;
                }

                .hero h1 {
                    font-size: 24px;
                    margin-bottom: 10px;
                }

                .hero p {
                    font-size: 14px;
                }
            }
        </style>
    @endif
</head>
<body>
    <!-- Header -->
    <header class="header-modern">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <a href="{{ route('shop.index') }}" class="logo-modern">
                    <div class="logo-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-name">Studio3D</span>
                        <span class="logo-tagline">Loja Online</span>
                    </div>
                </a>

                <!-- Navigation -->
                <nav class="nav-modern">
                    <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.index') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Início</span>
                    </a>
                    <a href="{{ route('orders.search-form') }}" class="nav-link">
                        <i class="fas fa-box-open"></i>
                        <span>Meus Pedidos</span>
                    </a>
                </nav>

                <!-- Actions -->
                <div class="header-actions">
                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="cart-btn-modern">
                        <div class="cart-icon-wrapper">
                            <i class="fas fa-shopping-bag"></i>
                            <span id="cart-count" class="cart-badge">0</span>
                        </div>
                        <span class="cart-text">Carrinho</span>
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="user-btn-modern">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="login-btn-modern">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Entrar</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Toggle -->
                <div class="mobile-actions">
                    <a href="{{ route('cart.index') }}" class="mobile-cart-btn">
                        <i class="fas fa-shopping-bag"></i>
                        <span id="cart-count-mobile" class="cart-badge-mobile">0</span>
                    </a>
                    <button class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Menu">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>

    <!-- Mobile Menu -->
    <nav class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <a href="{{ route('shop.index') }}" class="logo-modern">
                <div class="logo-icon">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="logo-text">
                    <span class="logo-name">Studio3D</span>
                    <span class="logo-tagline">Loja Online</span>
                </div>
            </a>
            <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="Fechar menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-menu-links">
            <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.index') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Início
            </a>
            <a href="{{ route('orders.search-form') }}">
                <i class="fas fa-box-open"></i> Meus Pedidos
            </a>
            <a href="{{ route('cart.index') }}">
                <i class="fas fa-shopping-bag"></i> Carrinho
            </a>
        </div>
        <div class="mobile-auth-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn">
                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                </a>
            @else
                <a href="{{ route('login') }}" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Entrar na Conta
                </a>
            @endauth
        </div>
    </nav>

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
        // Mobile Menu Functions
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.querySelector('.mobile-menu-overlay');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.querySelector('.mobile-menu-overlay');
            menu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });

        // Atualizar contador de carrinho via servidor
        function updateCartCount() {
            // O contador agora é atualizado automaticamente pelo servidor
            // Podemos fazer uma requisição AJAX se necessário para atualizar dinamicamente
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
            } catch (error) {
                console.error('Erro ao adicionar ao carrinho:', error);
                alert('Erro ao processar sua solicitação. Por favor, tente novamente.');
                return false;
            }
        }

        // Chamar ao carregar a página
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>

    @yield('scripts')
</body>
</html>
