<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Estatísticas de Produtos
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $avgProductPrice = Product::avg('price');

        // Estatísticas de Vendas
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $totalRevenue = Order::sum('total');
        $thisMonthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Produtos mais vendidos
        $topProducts = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('order_items.product_name, COUNT(*) as sales_count, SUM(order_items.quantity) as total_quantity')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Últimos pedidos
        $recentOrders = Order::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Categorias de produtos
        $categories = Category::withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function ($category) {
                return (object) [
                    'category' => $category->name,
                    'count' => $category->products_count
                ];
            });

        // Receita por status
        $revenueByStatus = Order::selectRaw('status, SUM(total) as total, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('dashboard', compact(
            'totalProducts',
            'activeProducts',
            'avgProductPrice',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'totalRevenue',
            'thisMonthRevenue',
            'topProducts',
            'recentOrders',
            'categories',
            'revenueByStatus'
        ));
    }
}
