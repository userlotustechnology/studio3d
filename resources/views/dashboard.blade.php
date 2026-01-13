@extends('layouts.main')

@section('title', 'Dashboard - Studio3D')

@section('content')
<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="fs-28 fw-bold text-secondary mb-1">Dashboard</h1>
                        <p class="text-body mb-0">Visão geral do desempenho da sua loja</p>
                    </div>
                    <div>
                        <span class="badge bg-primary fs-14 px-3 py-2">
                            <i class="material-symbols-outlined fs-18 align-middle me-1">calendar_today</i>
                            {{ now()->translatedFormat('d \d\e F, Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

<!-- KPI Cards Row 1 -->
<div class="row mb-4">
    <div class="col-xxl-3 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 h-100 hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="material-symbols-outlined text-white fs-28">attach_money</i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <span class="d-block text-body fs-14 mb-1">Receita Total</span>
                        <h3 class="fw-bold text-secondary fs-24 mb-0">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                    </div>
                </div>
                @php
                    $isGrowthPositive = $revenueGrowth >= 0;
                @endphp
                <div class="d-flex align-items-center">
                    <span class="badge {{ $isGrowthPositive ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $isGrowthPositive ? 'success' : 'danger' }} fs-13 px-2 py-1">
                        <i class="material-symbols-outlined fs-16 align-middle">{{ $isGrowthPositive ? 'trending_up' : 'trending_down' }}</i>
                        {{ number_format(abs($revenueGrowth), 1) }}%
                    </span>
                    <span class="text-body fs-13 ms-2">vs. mês anterior</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 h-100 hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 55px; height: 55px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="material-symbols-outlined text-white fs-28">shopping_cart</i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <span class="d-block text-body fs-14 mb-1">Total de Pedidos</span>
                        <h3 class="fw-bold text-secondary fs-24 mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning bg-opacity-10 text-warning fs-13 px-2 py-1">
                        <i class="material-symbols-outlined fs-16 align-middle">schedule</i>
                        {{ $pendingOrders }} pendentes
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 h-100 hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 55px; height: 55px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="material-symbols-outlined text-white fs-28">people</i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <span class="d-block text-body fs-14 mb-1">Clientes</span>
                        <h3 class="fw-bold text-secondary fs-24 mb-0">{{ $totalCustomers }}</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success bg-opacity-10 text-success fs-13 px-2 py-1">
                        <i class="material-symbols-outlined fs-16 align-middle">add</i>
                        {{ $newCustomersThisMonth }} novos este mês
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 h-100 hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 55px; height: 55px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="material-symbols-outlined text-white fs-28">inventory_2</i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <span class="d-block text-body fs-14 mb-1">Produtos Ativos</span>
                        <h3 class="fw-bold text-secondary fs-24 mb-0">{{ $activeProducts }}</h3>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    @if($lowStockProducts > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger fs-13 px-2 py-1">
                            <i class="material-symbols-outlined fs-16 align-middle">warning</i>
                            {{ $lowStockProducts }} com estoque baixo
                        </span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success fs-13 px-2 py-1">
                            <i class="material-symbols-outlined fs-16 align-middle">check_circle</i>
                            Estoques saudáveis
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Metrics -->
<div class="row mb-4">
    <div class="col-xxl-2 col-lg-4 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="material-symbols-outlined text-primary" style="font-size: 40px;">today</i>
                </div>
                <h4 class="fw-bold text-secondary mb-1">R$ {{ number_format($todayRevenue, 2, ',', '.') }}</h4>
                <p class="text-body fs-13 mb-0">Receita Hoje</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-2 col-lg-4 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="material-symbols-outlined text-success" style="font-size: 40px;">date_range</i>
                </div>
                <h4 class="fw-bold text-secondary mb-1">R$ {{ number_format($thisWeekRevenue, 2, ',', '.') }}</h4>
                <p class="text-body fs-13 mb-0">Receita Semanal</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-2 col-lg-4 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="material-symbols-outlined text-info" style="font-size: 40px;">calendar_month</i>
                </div>
                <h4 class="fw-bold text-secondary mb-1">R$ {{ number_format($thisMonthRevenue, 2, ',', '.') }}</h4>
                <p class="text-body fs-13 mb-0">Receita Mensal</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-2 col-lg-4 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="material-symbols-outlined text-warning" style="font-size: 40px;">sell</i>
                </div>
                <h4 class="fw-bold text-secondary mb-1">R$ {{ number_format($avgOrderValue, 2, ',', '.') }}</h4>
                <p class="text-body fs-13 mb-0">Ticket Médio</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-2 col-lg-4 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="material-symbols-outlined text-purple" style="font-size: 40px;">percent</i>
                </div>
                <h4 class="fw-bold text-secondary mb-1">{{ number_format($conversionRate, 1) }}%</h4>
                <p class="text-body fs-13 mb-0">Taxa Entrega</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-2 col-lg-4 col-sm-6 mb-4">
        <div class="card border-0 rounded-10 text-center h-100">
            <div class="card-body">
                <div class="mb-3">
                    <i class="material-symbols-outlined text-danger" style="font-size: 40px;">local_offer</i>
                </div>
                <h4 class="fw-bold text-secondary mb-1">R$ {{ number_format($avgProductPrice, 2, ',', '.') }}</h4>
                <p class="text-body fs-13 mb-0">Preço Médio</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Revenue Chart -->
    <div class="col-xxl-8 mb-4">
        <div class="card border-0 rounded-10 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-secondary mb-0">Receita nos Últimos 6 Meses</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Receita
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Receita</a></li>
                            <li><a class="dropdown-item" href="#">Pedidos</a></li>
                        </ul>
                    </div>
                </div>
                <div id="revenueChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Orders by Day Chart -->
    <div class="col-xxl-4 mb-4">
        <div class="card border-0 rounded-10 h-100">
            <div class="card-body">
                <h5 class="fw-bold text-secondary mb-4">Pedidos por Dia (7 dias)</h5>
                <div id="ordersByDayChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Status Cards and Categories -->
<div class="row mb-4">
    <div class="col-xxl-8 mb-4">
        <div class="card border-0 rounded-10 h-100">
            <div class="card-body">
                <h5 class="fw-bold text-secondary mb-4">Status dos Pedidos</h5>
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-10 border border-warning border-opacity-25" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="material-symbols-outlined text-warning fs-24">schedule</i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="d-block text-body fs-13 mb-1">Pendentes</span>
                                    <h4 class="fw-bold text-warning mb-0">{{ $pendingOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-10 border border-info border-opacity-25" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="material-symbols-outlined text-info fs-24">autorenew</i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="d-block text-body fs-13 mb-1">Processando</span>
                                    <h4 class="fw-bold text-info mb-0">{{ $processingOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-10 border border-primary border-opacity-25" style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="material-symbols-outlined text-primary fs-24">local_shipping</i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="d-block text-body fs-13 mb-1">Enviados</span>
                                    <h4 class="fw-bold text-primary mb-0">{{ $shippedOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-10 border border-success border-opacity-25" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="material-symbols-outlined text-success fs-24">check_circle</i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="d-block text-body fs-13 mb-1">Entregues</span>
                                    <h4 class="fw-bold text-success mb-0">{{ $deliveredOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-10 border border-danger border-opacity-25" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="material-symbols-outlined text-danger fs-24">cancel</i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="d-block text-body fs-13 mb-1">Cancelados</span>
                                    <h4 class="fw-bold text-danger mb-0">{{ $cancelledOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="p-3 rounded-10 border border-secondary border-opacity-25" style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary bg-opacity-25" style="width: 45px; height: 45px;">
                                        <i class="material-symbols-outlined text-secondary fs-24">summarize</i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="d-block text-body fs-13 mb-1">Total</span>
                                    <h4 class="fw-bold text-secondary mb-0">{{ $totalOrders }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="col-xxl-4 mb-4">
        <div class="card border-0 rounded-10 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-secondary mb-0">Categorias</h5>
                    <span class="badge bg-primary">{{ $categories->count() }}</span>
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    @foreach($categories as $category)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; min-width: 35px;">
                                    <i class="material-symbols-outlined text-primary fs-18">category</i>
                                </div>
                                <span class="ms-3 text-secondary fw-medium">{{ $category->category }}</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $category->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales by Category and Top Customers -->
<div class="row mb-4">
    <div class="col-xxl-6 mb-4">
        <div class="card border-0 rounded-10 h-100">
            <div class="card-body">
                <h5 class="fw-bold text-secondary mb-4">Vendas por Categoria (Top 5)</h5>
                <div id="salesByCategoryChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl-6 mb-4">
        <div class="card border-0 rounded-10 h-100">
            <div class="card-body">
                <h5 class="fw-bold text-secondary mb-4">Melhores Clientes</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-body fs-13">#</th>
                                <th class="text-body fs-13">Cliente</th>
                                <th class="text-body fs-13 text-center">Pedidos</th>
                                <th class="text-body fs-13 text-end">Total Gasto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers as $index => $customer)
                                <tr>
                                    <td>
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 30px; height: 30px;">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="d-block text-secondary fw-medium">{{ $customer->customer->name ?? 'Cliente' }}</span>
                                            <span class="text-body fs-12">{{ $customer->customer->email ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info">{{ $customer->orders_count }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-success fw-bold">R$ {{ number_format($customer->total_spent, 2, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-body py-4">Nenhum cliente registrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products and Recent Orders -->
<div class="row">
    <div class="col-xxl-6 mb-4">
        <div class="card border-0 rounded-10">
            <div class="card-body">
                <h5 class="fw-bold text-secondary mb-4">Produtos Mais Vendidos</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-body fs-13">#</th>
                                <th class="text-body fs-13">Produto</th>
                                <th class="text-body fs-13 text-center">Qtd</th>
                                <th class="text-body fs-13 text-end">Receita</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $index => $product)
                                <tr>
                                    <td>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                             style="width: 30px; height: 30px; background: linear-gradient(135deg, {{ ['#667eea', '#f093fb', '#4facfe', '#fa709a', '#fbc2eb'][$index % 5] }} 0%, {{ ['#764ba2', '#f5576c', '#00f2fe', '#fee140', '#a6c1ee'][$index % 5] }} 100%);">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="d-block text-secondary fw-medium">{{ $product->product_name }}</span>
                                            <span class="text-body fs-12">{{ $product->sales_count }} vendas</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $product->total_quantity }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-success fw-bold">R$ {{ number_format($product->total_revenue, 2, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-body py-4">Nenhuma venda registrada</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-6 mb-4">
        <div class="card border-0 rounded-10">
            <div class="card-body">
                <h5 class="fw-bold text-secondary mb-4">Últimos Pedidos</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-body fs-13">Pedido</th>
                                <th class="text-body fs-13">Cliente</th>
                                <th class="text-body fs-13 text-center">Status</th>
                                <th class="text-body fs-13 text-end">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="d-block text-secondary fw-medium fs-14">{{ $order->order_number }}</span>
                                            <span class="text-body fs-12">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary">{{ $order->customer->name ?? 'Cliente' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['label' => 'Pendente', 'color' => 'warning'],
                                                'processing' => ['label' => 'Processando', 'color' => 'info'],
                                                'shipped' => ['label' => 'Enviado', 'color' => 'primary'],
                                                'delivered' => ['label' => 'Entregue', 'color' => 'success'],
                                                'cancelled' => ['label' => 'Cancelado', 'color' => 'danger'],
                                            ];
                                            $status = $statusConfig[$order->status] ?? ['label' => $order->status, 'color' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $status['color'] }} bg-opacity-10 text-{{ $status['color'] }}">
                                            {{ $status['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-secondary fw-bold">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-body py-4">Nenhum pedido registrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    transform: translateY(-2px);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueData = @json($revenueByMonth);
    const revenueOptions = {
        series: [{
            name: 'Receita',
            data: revenueData.map(item => parseFloat(item.revenue))
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: revenueData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('pt-BR', { month: 'short', year: 'numeric' });
            }),
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }
        },
        colors: ['#667eea'],
        grid: {
            borderColor: '#f1f1f1'
        }
    };
    const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
    revenueChart.render();

    // Orders by Day Chart
    const ordersByDayData = @json($ordersByDay);
    const ordersByDayOptions = {
        series: [{
            name: 'Pedidos',
            data: ordersByDayData.map(item => item.count)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                distributed: true,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
        },
        xaxis: {
            categories: ordersByDayData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' });
            }),
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Quantidade'
            }
        },
        colors: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#fa709a'],
        legend: {
            show: false
        }
    };
    const ordersByDayChart = new ApexCharts(document.querySelector("#ordersByDayChart"), ordersByDayOptions);
    ordersByDayChart.render();

    // Sales by Category Chart
    const salesByCategoryData = @json($salesByCategory);
    const categoryOptions = {
        series: [{
            name: 'Receita',
            data: salesByCategoryData.map(item => parseFloat(item.revenue))
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 8,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
            },
            offsetX: 50,
            style: {
                fontSize: '11px',
                colors: ['#304758']
            }
        },
        xaxis: {
            categories: salesByCategoryData.map(item => item.category),
            labels: {
                formatter: function(value) {
                    return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                }
            }
        },
        colors: ['#4facfe'],
        grid: {
            borderColor: '#f1f1f1'
        }
    };
    const categoryChart = new ApexCharts(document.querySelector("#salesByCategoryChart"), categoryOptions);
    categoryChart.render();
});
</script>
    </div>
</div>
@endsection
