<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// Rotas públicas - Loja
Route::get('/', [ProductController::class, 'shop'])->name('shop.index');
Route::get('/categoria/{category}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/produto/{id}', [ProductController::class, 'show'])->name('shop.show');

// Rotas do Carrinho
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrinho/atualizar/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho/remover/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout/processar', [CartController::class, 'processCheckout'])->name('cart.process-checkout');
Route::get('/pedido/{order}/sucesso', [CartController::class, 'orderSuccess'])->name('order.success');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rotas específicas para administradores
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', function () {
            return view('admin.users.index');
        })->name('admin.users.index');
        
        Route::get('/admin/settings', function () {
            return view('admin.settings.index');
        })->name('admin.settings.index');
    });
});
