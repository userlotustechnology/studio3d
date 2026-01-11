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
