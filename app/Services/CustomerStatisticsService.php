<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerStatisticsService
{
    /**
     * Retorna estatísticas gerais do cliente
     */
    public function getCustomerStatistics(Customer $customer): array
    {
        $orders = $customer->orders()
            ->where('is_draft', false)
            ->get();

        $totalSpent = $orders->sum('total');
        $totalOrders = $orders->count();
        $averageTicket = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

        return [
            'total_spent' => $totalSpent,
            'total_orders' => $totalOrders,
            'average_ticket' => $averageTicket,
            'first_purchase_date' => $orders->min('created_at'),
            'last_purchase_date' => $orders->max('created_at'),
            'total_items' => $orders->sum(function ($order) {
                return $order->items->sum('quantity');
            }),
        ];
    }

    /**
     * Retorna os principais produtos comprados pelo cliente
     */
    public function getTopProducts(Customer $customer, int $limit = 5): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.customer_id', $customer->id)
            ->where('orders.is_draft', false)
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as purchase_count'),
                DB::raw('SUM(order_items.subtotal) as total_amount'),
                DB::raw('AVG(order_items.product_price) as average_price')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }

    /**
     * Retorna o histórico de compras do cliente com paginação
     */
    public function getPurchaseHistory(Customer $customer, int $perPage = 10)
    {
        return $customer->orders()
            ->where('is_draft', false)
            ->with(['items', 'shippingCompany', 'billingAddress', 'shippingAddress'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Retorna análise de padrão de compras
     */
    public function getPurchasePattern(Customer $customer): array
    {
        $orders = $customer->orders()
            ->where('is_draft', false)
            ->orderBy('created_at')
            ->get();

        if ($orders->isEmpty()) {
            return [
                'frequency' => 'Sem compras',
                'last_30_days' => 0,
                'last_90_days' => 0,
                'last_year' => 0,
                'trend' => 'stable',
            ];
        }

        $now = now();
        $last30 = $orders->filter(fn($order) => $order->created_at->diffInDays($now) <= 30)->count();
        $last90 = $orders->filter(fn($order) => $order->created_at->diffInDays($now) <= 90)->count();
        $lastYear = $orders->filter(fn($order) => $order->created_at->diffInYears($now) < 1)->count();

        $trend = 'stable';
        if ($last30 > $last90 / 3) {
            $trend = 'increasing';
        } elseif ($last30 < $last90 / 3) {
            $trend = 'decreasing';
        }

        return [
            'frequency' => $this->determineFrequency($orders),
            'last_30_days' => $last30,
            'last_90_days' => $last90,
            'last_year' => $lastYear,
            'trend' => $trend,
            'days_between_purchases' => $this->getAverageDaysBetweenPurchases($orders),
        ];
    }

    /**
     * Retorna informações de satisfação/status dos pedidos
     */
    public function getOrderStatusSummary(Customer $customer): array
    {
        $orders = $customer->orders()
            ->where('is_draft', false)
            ->get();

        return [
            'completed' => $orders->where('status', 'delivered')->count(),
            'pending' => $orders->where('status', 'pending')->count(),
            'processing' => $orders->where('status', 'processing')->count(),
            'shipped' => $orders->where('status', 'shipped')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Calcula o valor total de produtos retornados
     */
    public function getReturnedOrdersValue(Customer $customer): float
    {
        return $customer->orders()
            ->where('status', 'cancelled')
            ->sum('total');
    }

    /**
     * Determina a frequência de compras
     */
    private function determineFrequency(Collection $orders): string
    {
        if ($orders->count() < 2) {
            return 'Uma única compra';
        }

        $daysBetween = $this->getAverageDaysBetweenPurchases($orders);

        if ($daysBetween <= 30) {
            return 'Muito frequente (< 30 dias)';
        } elseif ($daysBetween <= 90) {
            return 'Frequente (30-90 dias)';
        } elseif ($daysBetween <= 180) {
            return 'Ocasional (90-180 dias)';
        } else {
            return 'Raro (> 180 dias)';
        }
    }

    /**
     * Calcula a média de dias entre compras
     */
    private function getAverageDaysBetweenPurchases(Collection $orders): float
    {
        if ($orders->count() < 2) {
            return 0;
        }

        $sortedDates = $orders->pluck('created_at')
            ->sort()
            ->values();

        $intervals = [];
        for ($i = 1; $i < $sortedDates->count(); $i++) {
            $intervals[] = $sortedDates[$i]->diffInDays($sortedDates[$i - 1]);
        }

        return count($intervals) > 0 ? array_sum($intervals) / count($intervals) : 0;
    }

    /**
     * Retorna produtos mais lucrativos do cliente
     */
    public function getMostProfitableProducts(Customer $customer, int $limit = 5): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.customer_id', $customer->id)
            ->where('orders.is_draft', false)
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('COUNT(*) as times_purchased')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }
}
