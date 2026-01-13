<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(): View
    {
        // Métricas gerais
        $totalOrders = Order::where('is_draft', false)->count();
        $totalRevenue = Order::where('is_draft', false)->sum('total');
        $totalSubtotal = Order::where('is_draft', false)->sum('subtotal');
        $totalShipping = Order::where('is_draft', false)->sum('shipping_cost');
        $totalDiscount = Order::where('is_draft', false)->sum('discount');
        
        // Métricas de frete
        $totalShippingRevenue = Order::where('is_draft', false)->sum('shipping_cost'); // Valor pago pelos clientes
        $totalShippingCost = Order::where('is_draft', false)->sum('carrier_shipping_cost'); // Custo real com transportadora
        $shippingProfit = $totalShippingRevenue - $totalShippingCost; // Lucro com frete
        $ordersWithShipping = Order::where('is_draft', false)->where('shipping_cost', '>', 0)->count();
        $averageShippingRevenue = $ordersWithShipping > 0 ? $totalShippingRevenue / $ordersWithShipping : 0;
        $averageShippingCost = $ordersWithShipping > 0 ? $totalShippingCost / $ordersWithShipping : 0;
        
        // Estados com mais envios
        $topShippingStates = DB::table('orders')
            ->join('addresses', 'orders.shipping_address_id', '=', 'addresses.id')
            ->where('orders.is_draft', false)
            ->where('orders.shipping_cost', '>', 0)
            ->selectRaw('addresses.state, COUNT(*) as total_orders, SUM(orders.shipping_cost) as total_revenue, SUM(orders.carrier_shipping_cost) as total_cost')
            ->groupBy('addresses.state')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();
        
        // Métricas de cashback
        $totalCashbackGranted = Order::where('is_draft', false)->sum('cashback_amount'); // Total concedido
        $totalCashbackActive = Order::where('is_draft', false)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('cashback_amount'); // Cashback ativo (não estornado)
        $totalCashbackRefunded = Order::where('is_draft', false)
            ->whereIn('status', ['cancelled', 'refunded'])
            ->sum('cashback_amount'); // Cashback estornado
        $ordersWithCashback = Order::where('is_draft', false)->where('cashback_amount', '>', 0)->count();
        $averageCashback = $ordersWithCashback > 0 ? $totalCashbackGranted / $ordersWithCashback : 0;
        
        // Métricas de desconto de forma de pagamento
        $totalPaymentDiscount = Order::where('is_draft', false)->sum('discount'); // Total de descontos concedidos
        $totalPaymentDiscountActive = Order::where('is_draft', false)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('discount'); // Descontos ativos
        $totalPaymentDiscountRefunded = Order::where('is_draft', false)
            ->whereIn('status', ['cancelled', 'refunded'])
            ->sum('discount'); // Descontos estornados
        $ordersWithPaymentDiscount = Order::where('is_draft', false)->where('discount', '>', 0)->count();
        $averagePaymentDiscount = $ordersWithPaymentDiscount > 0 ? $totalPaymentDiscount / $ordersWithPaymentDiscount : 0;
        
        // Descontos por forma de pagamento
        $discountsByPaymentMethod = DB::table('orders')
            ->join('payment_methods', 'orders.payment_method', '=', 'payment_methods.id')
            ->where('orders.is_draft', false)
            ->where('orders.discount', '>', 0)
            ->selectRaw('payment_methods.name as payment_method_name, COUNT(*) as total_orders, SUM(orders.discount) as total_discount, AVG(orders.discount) as average_discount')
            ->groupBy('payment_methods.id', 'payment_methods.name')
            ->orderByDesc('total_discount')
            ->get();
        
        // Análise de Produtos
        $productAnalysis = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.is_draft', false)
            ->selectRaw('
                products.id,
                products.name,
                products.price,
                products.cost_price,
                SUM(order_items.quantity) as total_quantity,
                SUM(order_items.subtotal) as total_revenue,
                SUM(order_items.quantity * products.cost_price) as total_cost,
                (products.price - products.cost_price) as unit_profit,
                ((products.price - products.cost_price) / products.price * 100) as profit_margin_percentage
            ')
            ->groupBy('products.id', 'products.name', 'products.price', 'products.cost_price')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
        
        // Métricas gerais de produtos
        $totalProductRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.is_draft', false)
            ->sum('order_items.subtotal');
        
        $totalProductCost = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.is_draft', false)
            ->selectRaw('SUM(order_items.quantity * products.cost_price) as total')
            ->value('total');
        
        $totalProductProfit = $totalProductRevenue - ($totalProductCost ?? 0);
        $averageProfitMargin = $totalProductRevenue > 0 ? (($totalProductProfit / $totalProductRevenue) * 100) : 0;
        
        // Contagem por status
        $pendingOrders = Order::where('is_draft', false)->where('status', 'pending')->count();
        $processingOrders = Order::where('is_draft', false)->where('status', 'processing')->count();
        $shippedOrders = Order::where('is_draft', false)->where('status', 'shipped')->count();
        $deliveredOrders = Order::where('is_draft', false)->where('status', 'delivered')->count();
        $cancelledOrders = Order::where('is_draft', false)->where('status', 'cancelled')->count();
        
        // Receita por mês (últimos 12 meses)
        $revenueByMonth = $this->getRevenueByMonth(12);
        
        // Receita por status
        $revenueByStatus = Order::where('is_draft', false)
            ->selectRaw('status, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('status')
            ->get();
        
        // Tickets médios
        $averageTicket = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Produtos mais vendidos
        $topProducts = $this->getTopProducts(10);
        
        // Informações por período
        $periods = [
            'today' => [
                'label' => 'Hoje',
                'start' => Carbon::today(),
                'end' => Carbon::today()->endOfDay(),
            ],
            'week' => [
                'label' => 'Esta Semana',
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek(),
            ],
            'month' => [
                'label' => 'Este Mês',
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
            'year' => [
                'label' => 'Este Ano',
                'start' => Carbon::now()->startOfYear(),
                'end' => Carbon::now()->endOfYear(),
            ],
        ];

        $periodMetrics = [];
        foreach ($periods as $key => $period) {
            $periodMetrics[$key] = [
                'label' => $period['label'],
                'orders' => Order::where('is_draft', false)
                    ->whereBetween('created_at', [$period['start'], $period['end']])
                    ->count(),
                'revenue' => Order::where('is_draft', false)
                    ->whereBetween('created_at', [$period['start'], $period['end']])
                    ->sum('total'),
            ];
        }

        return view('admin.finance.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalSubtotal',
            'totalShipping',
            'totalDiscount',
            'totalShippingRevenue',
            'totalShippingCost',
            'shippingProfit',
            'ordersWithShipping',
            'averageShippingRevenue',
            'averageShippingCost',
            'topShippingStates',
            'totalCashbackGranted',
            'totalCashbackActive',
            'totalCashbackRefunded',
            'ordersWithCashback',
            'averageCashback',
            'totalPaymentDiscount',
            'totalPaymentDiscountActive',
            'totalPaymentDiscountRefunded',
            'ordersWithPaymentDiscount',
            'averagePaymentDiscount',
            'discountsByPaymentMethod',
            'productAnalysis',
            'totalProductRevenue',
            'totalProductCost',
            'totalProductProfit',
            'averageProfitMargin',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'averageTicket',
            'revenueByMonth',
            'revenueByStatus',
            'topProducts',
            'periodMetrics'
        ));
    }

    public function sales(): View
    {
        $salesByDay = Order::where('is_draft', false)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'asc')
            ->get();

        $orders = Order::where('is_draft', false)
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.sales', compact('salesByDay', 'orders'));
    }

    private function getRevenueByMonth($months = 12)
    {
        $data = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $revenue = Order::where('is_draft', false)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total');
            
            $data[] = [
                'month' => $date->format('M/y'),
                'revenue' => (float) $revenue,
            ];
        }
        
        return $data;
    }

    private function getTopProducts($limit = 10)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.is_draft', false)
            ->select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }
}
