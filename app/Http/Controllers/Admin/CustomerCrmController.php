<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerStatisticsService;

class CustomerCrmController extends Controller
{
    public function __construct(private CustomerStatisticsService $statisticsService)
    {
    }

    /**
     * Lista todos os clientes que fizeram compras
     */
    public function index()
    {
        $customers = Customer::withCount(['orders' => function ($query) {
            $query->where('is_draft', false);
        }])
            ->with(['orders' => function ($query) {
                $query->where('is_draft', false)
                    ->latest()
                    ->limit(1);
            }])
            ->whereHas('orders', function ($query) {
                $query->where('is_draft', false);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        // Adiciona estatísticas rápidas para cada cliente
        $customers->getCollection()->transform(function ($customer) {
            $stats = $this->statisticsService->getCustomerStatistics($customer);
            $customer->total_spent = $stats['total_spent'];
            $customer->average_ticket = $stats['average_ticket'];
            return $customer;
        });

        return view('admin.crm.customers.index', [
            'customers' => $customers,
        ]);
    }

    /**
     * Exibe detalhes completos do cliente
     */
    public function show(Customer $customer)
    {
        // Verifica se o cliente tem alguma compra
        if (!$customer->orders()->where('is_draft', false)->exists()) {
            abort(404, 'Cliente não encontrado ou sem histórico de compras');
        }

        // Carrega as relações necessárias
        $customer->load(['orders' => function ($query) {
            $query->where('is_draft', false)
                ->with(['items.product', 'shippingCompany', 'billingAddress', 'shippingAddress'])
                ->orderByDesc('created_at');
        }]);

        // Obtém estatísticas
        $statistics = $this->statisticsService->getCustomerStatistics($customer);
        $topProducts = $this->statisticsService->getTopProducts($customer, 5);
        $purchasePattern = $this->statisticsService->getPurchasePattern($customer);
        $orderStatusSummary = $this->statisticsService->getOrderStatusSummary($customer);
        $mostProfitableProducts = $this->statisticsService->getMostProfitableProducts($customer, 5);
        $returnedOrdersValue = $this->statisticsService->getReturnedOrdersValue($customer);

        return view('admin.crm.customers.show', [
            'customer' => $customer,
            'statistics' => $statistics,
            'topProducts' => $topProducts,
            'purchasePattern' => $purchasePattern,
            'orderStatusSummary' => $orderStatusSummary,
            'mostProfitableProducts' => $mostProfitableProducts,
            'returnedOrdersValue' => $returnedOrdersValue,
        ]);
    }

    /**
     * Exibe os detalhes de um pedido específico do cliente
     */
    public function showOrder(Customer $customer, $orderId)
    {
        $order = $customer->orders()
            ->where('id', $orderId)
            ->where('is_draft', false)
            ->with(['items.product', 'shippingCompany', 'billingAddress', 'shippingAddress', 'statusHistories'])
            ->firstOrFail();

        return view('admin.crm.customers.order-detail', [
            'customer' => $customer,
            'order' => $order,
        ]);
    }

    /**
     * Busca clientes por nome, email ou CPF
     */
    public function search()
    {
        $search = request('q', '');

        if (strlen($search) < 2) {
            return response()->json(['results' => []]);
        }

        $customers = Customer::whereHas('orders', function ($query) {
            $query->where('is_draft', false);
        })
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'email', 'cpf')
            ->limit(10)
            ->get();

        return response()->json([
            'results' => $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'text' => "{$customer->name} ({$customer->email})",
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'cpf' => $customer->cpf,
                ];
            }),
        ]);
    }

    /**
     * Exporta dados dos clientes em CSV
     */
    public function export()
    {
        $customers = Customer::withCount(['orders' => function ($query) {
            $query->where('is_draft', false);
        }])
            ->whereHas('orders', function ($query) {
                $query->where('is_draft', false);
            })
            ->get();

        $fileName = 'clientes-crm-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nome', 'Email', 'CPF', 'Telefone', 'Total de Pedidos', 'Total Gasto', 'Ticket Médio', 'Data da Primeira Compra', 'Data da Última Compra']);

            foreach ($customers as $customer) {
                $stats = $this->statisticsService->getCustomerStatistics($customer);
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->cpf,
                    $customer->phone,
                    $stats['total_orders'],
                    number_format($stats['total_spent'], 2, ',', '.'),
                    number_format($stats['average_ticket'], 2, ',', '.'),
                    $stats['first_purchase_date']?->format('d/m/Y'),
                    $stats['last_purchase_date']?->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
