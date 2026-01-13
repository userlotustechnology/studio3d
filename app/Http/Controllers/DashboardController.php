<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Tempo de cache em minutos
     * Ajustar conforme necessidade de atualização dos dados
     */
    private const CACHE_TTL = 5; // 5 minutos
    
    public function index(): View
    {
        // Usar cache para métricas que não mudam frequentemente
        $cacheKey = 'dashboard_metrics_' . date('Y-m-d-H');
        
        $metrics = Cache::remember($cacheKey, now()->addMinutes(self::CACHE_TTL), function () {
            return $this->calculateMetrics();
        });
        
        // Dados que devem ser sempre atualizados (sem cache)
        $recentOrders = $this->getRecentOrders();
        
        return view('dashboard', array_merge($metrics, [
            'recentOrders' => $recentOrders,
        ]));
    }
    
    /**
     * Calcula todas as métricas do dashboard
     * Esta função é cacheada para melhor performance
     */
    private function calculateMetrics(): array
    {
        // Usar query única para pegar várias estatísticas de pedidos de uma vez
        $orderStats = DB::table('orders')
            ->where('is_draft', false)
            ->select([
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('AVG(total) as avg_order_value'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders'),
                DB::raw('SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing_orders'),
                DB::raw('SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_orders'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total ELSE 0 END) as today_revenue'),
                DB::raw('SUM(CASE WHEN YEARWEEK(created_at) = YEARWEEK(NOW()) THEN total ELSE 0 END) as week_revenue'),
                DB::raw('SUM(CASE WHEN YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW()) THEN total ELSE 0 END) as month_revenue'),
                DB::raw('SUM(CASE WHEN YEAR(created_at) = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND MONTH(created_at) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) THEN total ELSE 0 END) as last_month_revenue'),
            ])
            ->first();

        // Estatísticas de Produtos em uma query
        $productStats = DB::table('products')
            ->select([
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_products'),
                DB::raw('AVG(price) as avg_product_price'),
                DB::raw('SUM(CASE WHEN is_active = 1 AND stock < 10 THEN 1 ELSE 0 END) as low_stock_products'),
            ])
            ->first();

        // Estatísticas de Clientes em uma query
        $customerStats = DB::table('customers')
            ->select([
                DB::raw('COUNT(*) as total_customers'),
                DB::raw('SUM(CASE WHEN YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW()) THEN 1 ELSE 0 END) as new_customers_this_month'),
            ])
            ->first();

        // Cálculo de crescimento mensal
        $revenueGrowth = $orderStats->last_month_revenue > 0 
            ? (($orderStats->month_revenue - $orderStats->last_month_revenue) / $orderStats->last_month_revenue) * 100 
            : 0;

        // Taxa de conversão
        $conversionRate = $orderStats->total_orders > 0 
            ? ($orderStats->delivered_orders / $orderStats->total_orders) * 100 
            : 0;

        // Receita por mês (últimos 6 meses) - otimizado
        $revenueByMonth = DB::table('orders')
            ->where('is_draft', false)
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Pedidos por dia (última semana) - otimizado
        $ordersByDay = DB::table('orders')
            ->where('is_draft', false)
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Produtos mais vendidos - otimizado com LIMIT direto no SQL
        $topProducts = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.is_draft', false)
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

        // Categorias com contagem de produtos - eager loading
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->map(function ($category) {
                return (object) [
                    'category' => $category->name,
                    'count' => $category->products_count
                ];
            });

        // Vendas por categoria (top 5) - otimizado
        $salesByCategory = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.is_draft', false)
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

        // Clientes com mais pedidos - otimizado
        $topCustomers = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->where('orders.is_draft', false)
            ->whereNotNull('orders.customer_id')
            ->select(
                'customers.id as customer_id',
                'customers.name',
                'customers.email',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(orders.total) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                return (object) [
                    'customer' => (object) [
                        'name' => $customer->name,
                        'email' => $customer->email,
                    ],
                    'orders_count' => $customer->orders_count,
                    'total_spent' => $customer->total_spent,
                ];
            });

        // Receita por status
        $revenueByStatus = DB::table('orders')
            ->where('is_draft', false)
            ->select(
                'status',
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'totalProducts' => $productStats->total_products,
            'activeProducts' => $productStats->active_products,
            'avgProductPrice' => $productStats->avg_product_price,
            'lowStockProducts' => $productStats->low_stock_products,
            'totalOrders' => $orderStats->total_orders,
            'pendingOrders' => $orderStats->pending_orders,
            'processingOrders' => $orderStats->processing_orders,
            'shippedOrders' => $orderStats->shipped_orders,
            'deliveredOrders' => $orderStats->delivered_orders,
            'cancelledOrders' => $orderStats->cancelled_orders,
            'totalRevenue' => $orderStats->total_revenue,
            'todayRevenue' => $orderStats->today_revenue,
            'thisWeekRevenue' => $orderStats->week_revenue,
            'thisMonthRevenue' => $orderStats->month_revenue,
            'lastMonthRevenue' => $orderStats->last_month_revenue,
            'revenueGrowth' => $revenueGrowth,
            'avgOrderValue' => $orderStats->avg_order_value,
            'revenueByMonth' => $revenueByMonth,
            'ordersByDay' => $ordersByDay,
            'topProducts' => $topProducts,
            'categories' => $categories,
            'salesByCategory' => $salesByCategory,
            'totalCustomers' => $customerStats->total_customers,
            'newCustomersThisMonth' => $customerStats->new_customers_this_month,
            'topCustomers' => $topCustomers,
            'revenueByStatus' => $revenueByStatus,
            'conversionRate' => $conversionRate,
        ];
    }
    
    /**
     * Obtém pedidos recentes sem cache
     * Sempre deve mostrar os dados mais atualizados
     */
    private function getRecentOrders()
    {
        return Order::where('is_draft', false)
            ->with(['customer' => function ($query) {
                $query->select('id', 'name', 'email'); // Selecionar apenas campos necessários
            }])
            ->select('id', 'order_number', 'customer_id', 'status', 'total', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    }
    
    /**
     * Limpar cache do dashboard manualmente (útil após operações importantes)
     */
    public function clearCache()
    {
        $pattern = 'dashboard_metrics_*';
        
        // Laravel não tem cache pattern delete nativo, então precisamos usar tags ou flush
        // Para desenvolvimento, você pode adicionar uma rota admin para limpar o cache
        Cache::forget('dashboard_metrics_' . date('Y-m-d-H'));
        
        return response()->json(['message' => 'Cache limpo com sucesso']);
    }
}
