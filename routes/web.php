<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Models\Category;

// Rotas públicas - Loja
Route::get('/', [ProductController::class, 'shop'])->name('shop.index');
Route::get('/categoria/{category}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/produto/{id}', [ProductController::class, 'show'])->name('shop.show');

// Rotas do Carrinho
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::get('/carrinho/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/carrinho/cpf', [CartController::class, 'requestCpf'])->name('cart.request-cpf');
Route::post('/carrinho/adicionar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/atualizar/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho/remover/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout/processar', [CartController::class, 'processCheckout'])->name('cart.process-checkout');
Route::get('/pedido/{order}/sucesso', [CartController::class, 'orderSuccess'])->name('order.success');

// Rotas de Consulta de Pedidos (público)
Route::get('/consultar-pedido', [CartController::class, 'searchOrdersForm'])->name('orders.search-form');
Route::post('/consultar-pedido', [CartController::class, 'searchOrders'])->name('orders.search');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rotas específicas para administradores
    Route::middleware('role:admin')->group(function () {
        // Rotas de Gerenciamento de Usuários
        Route::resource('admin/users', UserController::class, [
            'parameters' => ['user' => 'user'],
            'names' => [
                'index' => 'admin.users.index',
                'create' => 'admin.users.create',
                'store' => 'admin.users.store',
                'show' => 'admin.users.show',
                'edit' => 'admin.users.edit',
                'update' => 'admin.users.update',
                'destroy' => 'admin.users.destroy'
            ]
        ]);
        
        Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings/store', [SettingController::class, 'storeSettings'])->name('admin.settings.store');
        Route::post('/admin/settings/system', [SettingController::class, 'systemSettings'])->name('admin.settings.system');
        
        // Rotas de Gerenciamento de Produtos
        Route::resource('admin/products', ProductController::class, [
            'parameters' => ['product' => 'product'],
            'names' => [
                'index' => 'admin.products.index',
                'create' => 'admin.products.create',
                'store' => 'admin.products.store',
                'show' => 'admin.products.show',
                'edit' => 'admin.products.edit',
                'update' => 'admin.products.update',
                'destroy' => 'admin.products.destroy'
            ]
        ]);
        
        // Rotas de Gerenciamento de Pedidos
        Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/admin/orders/pending', [OrderController::class, 'pending'])->name('admin.orders.pending');
        Route::get('/admin/orders/completed', [OrderController::class, 'completed'])->name('admin.orders.completed');
        Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/admin/orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        
        // Rotas de Gerenciamento de Categorias
        Route::resource('admin/categories', CategoryController::class, [
            'parameters' => ['category' => 'category'],
            'names' => [
                'index' => 'admin.categories.index',
                'create' => 'admin.categories.create',
                'store' => 'admin.categories.store',
                'show' => 'admin.categories.show',
                'edit' => 'admin.categories.edit',
                'update' => 'admin.categories.update',
                'destroy' => 'admin.categories.destroy'
            ]
        ]);
    });
});
