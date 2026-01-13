<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Relatório de Cashback Concedido
     */
    public function cashback(Request $request): View
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : now()->startOfMonth();
            
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : now()->endOfMonth();

        // Buscar pedidos com cashback no período
        $ordersQuery = Order::with('customer')
            ->where('cashback_amount', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc');

        // Filtrar por cliente se especificado
        if ($request->filled('customer_id')) {
            $ordersQuery->where('customer_id', $request->customer_id);
        }

        $orders = $ordersQuery->get();

        // Calcular totais
        $totalCashbackGranted = $orders->sum('cashback_amount');
        $totalCashbackActive = $orders->whereNotIn('status', ['cancelled', 'refunded'])->sum('cashback_amount');
        $totalCashbackRefunded = $orders->whereIn('status', ['cancelled', 'refunded'])->sum('cashback_amount');
        $totalOrders = $orders->count();
        $averageCashbackPerOrder = $totalOrders > 0 ? $totalCashbackGranted / $totalOrders : 0;

        // Agrupar por cliente
        $customersSummary = $orders->groupBy('customer_id')->map(function ($customerOrders) {
            $customer = $customerOrders->first()->customer;
            return [
                'customer' => $customer,
                'total_orders' => $customerOrders->count(),
                'total_cashback' => $customerOrders->sum('cashback_amount'),
                'active_cashback' => $customerOrders->whereNotIn('status', ['cancelled', 'refunded'])->sum('cashback_amount'),
                'refunded_cashback' => $customerOrders->whereIn('status', ['cancelled', 'refunded'])->sum('cashback_amount'),
            ];
        })->sortByDesc('total_cashback')->values();

        // Buscar todos os clientes para o filtro
        $customers = Customer::whereHas('orders', function ($query) {
            $query->where('cashback_amount', '>', 0);
        })->orderBy('name')->get();

        return view('admin.reports.cashback', compact(
            'orders',
            'totalCashbackGranted',
            'totalCashbackActive',
            'totalCashbackRefunded',
            'totalOrders',
            'averageCashbackPerOrder',
            'customersSummary',
            'customers',
            'startDate',
            'endDate'
        ));
    }
}
