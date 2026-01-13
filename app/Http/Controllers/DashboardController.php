<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Estatísticas de Produtos
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $avgProductPrice = Product::avg('price');
        $lowStockProducts = Product::where('stock', '<', 10)->where('is_active', true)->count();

        // Estatísticas de Vendas (apenas pedidos finalizados, não drafts)
        $totalOrders = Order::where('is_draft', false)->count();
        $pendingOrders = Order::where('is_draft', false)->where('status', 'pending')->count();
        $processingOrders = Order::where('is_draft', false)->where('status', 'processing')->count();
        $shippedOrders = Order::where('is_draft', false)->where('status', 'shipped')->count();
        $deliveredOrders = Order::where('is_draft', false)->where('status', 'delivered')->count();
        $cancelledOrders = Order::where('is_draft', false)->where('status', 'cancelled')->count();

        // Receitas
        $totalRevenue = Order::where('is_draft', false)->sum('total');
        $todayRevenue = Order::where('is_draft', false)
            ->whereDate('created_at', today())
            ->sum('total');
        $thisWeekRevenue = Order::where('is_draft', false)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total');
        $thisMonthRevenue = Order::where('is_draft', false)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $lastMonthRevenue = Order::where('is_draft', false)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        // Cálculo de crescimento mensal
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // Ticket médio
        $avgOrderValue = Order::where('is_draft', false)->avg('total');

        // Receita por mês (últimos 6 meses)
        $revenueByMonth = Order::where('is_draft', false)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Pedidos por dia (última semana)
        $ordersByDay = Order::where('is_draft', false)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Produtos mais vendidos (apenas pedidos finalizados)
        $topProducts = Order::where('is_draft', false)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select(
                'order_items.product_name',
                DB::raw('COUNT(DISTINCT orders.id) as sales_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Últimos pedidos (apenas pedidos finalizados)
        $recentOrders = Order::where('is_draft', false)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Categorias de produtos com vendas
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

        // Vendas por categoria (top 5)
        $salesByCategory = Order::where('orders.is_draft', false)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category',
                DB::raw('COUNT(DISTINCT orders.id) as orders'),
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Estatísticas de clientes
        $totalCustomers = Customer::count();
        $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Clientes com mais pedidos
        $topCustomers = Order::where('is_draft', false)
            ->select(
                'customer_id',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as total_spent')
            )
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->with('customer')
            ->get();

        // Receita por status
        $revenueByStatus = Order::where('is_draft', false)
            ->select('status', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Taxa de conversão (simulada - pode ser ajustada com dados reais de visitas)
        $conversionRate = $totalOrders > 0 ? ($deliveredOrders / $totalOrders) * 100 : 0;

        return view('dashboard', compact(
            'totalProducts',
            'activeProducts',
            'avgProductPrice',
            'lowStockProducts',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalRevenue',
            'todayRevenue',
            'thisWeekRevenue',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'revenueGrowth',
            'avgOrderValue',
            'revenueByMonth',
            'ordersByDay',
            'topProducts',
            'recentOrders',
            'categories',
            'salesByCategory',
            'totalCustomers',
            'newCustomersThisMonth',
            'topCustomers',
            'revenueByStatus',
            'conversionRate'
        ));
    }
}
