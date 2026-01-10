@extends('layouts.main')

@section('title', 'Dashboard - Loja Online')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin-bottom: 10px;">Dashboard</h1>
            <p style="color: #6b7280; font-size: 16px;">Bem-vindo ao painel de controle da sua loja</p>
        </div>

        <!-- KPI Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <!-- Total Produtos -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <div>
                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Total de Produtos</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">{{ $totalProducts }}</h2>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-box" style="font-size: 24px; color: #3b82f6;"></i>
                    </div>
                </div>
                <p style="color: #10b981; font-size: 13px; margin: 0;">
                    <i class="fas fa-check-circle"></i> {{ $activeProducts }} ativos
                </p>
            </div>

            <!-- Preço Médio -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <div>
                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Preço Médio</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">R$ {{ number_format($avgProductPrice, 2, ',', '.') }}</h2>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #fecaca; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tag" style="font-size: 24px; color: #f87171;"></i>
                    </div>
                </div>
                <p style="color: #6b7280; font-size: 13px; margin: 0;">
                    Entre todos os produtos
                </p>
            </div>

            <!-- Total Pedidos -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <div>
                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Total de Pedidos</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">{{ $totalOrders }}</h2>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #d1fae5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shopping-cart" style="font-size: 24px; color: #10b981;"></i>
                    </div>
                </div>
                <p style="color: #f59e0b; font-size: 13px; margin: 0;">
                    <i class="fas fa-exclamation-circle"></i> {{ $pendingOrders }} pendentes
                </p>
            </div>

            <!-- Receita Total -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <div>
                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">Receita Total</p>
                        <h2 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h2>
                    </div>
                    <div style="width: 50px; height: 50px; background-color: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-dollar-sign" style="font-size: 24px; color: #f59e0b;"></i>
                    </div>
                </div>
                <p style="color: #10b981; font-size: 13px; margin: 0;">
                    <i class="fas fa-arrow-up"></i> R$ {{ number_format($thisMonthRevenue, 2, ',', '.') }} este mês
                </p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 40px;">
            <!-- Status dos Pedidos -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">Status dos Pedidos</h3>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div style="padding: 15px; background-color: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b;">
                        <p style="color: #92400e; font-size: 13px; margin: 0 0 8px 0; font-weight: 600;">PENDENTES</p>
                        <p style="color: #b45309; font-size: 24px; font-weight: 700; margin: 0;">{{ $pendingOrders }}</p>
                    </div>
                    <div style="padding: 15px; background-color: #dbeafe; border-radius: 6px; border-left: 4px solid #3b82f6;">
                        <p style="color: #1e40af; font-size: 13px; margin: 0 0 8px 0; font-weight: 600;">PROCESSANDO</p>
                        <p style="color: #1e3a8a; font-size: 24px; font-weight: 700; margin: 0;">{{ $processingOrders }}</p>
                    </div>
                    <div style="padding: 15px; background-color: #d1fae5; border-radius: 6px; border-left: 4px solid #10b981;">
                        <p style="color: #065f46; font-size: 13px; margin: 0 0 8px 0; font-weight: 600;">ENVIADOS</p>
                        <p style="color: #047857; font-size: 24px; font-weight: 700; margin: 0;">{{ $shippedOrders }}</p>
                    </div>
                    <div style="padding: 15px; background-color: #d1fae5; border-radius: 6px; border-left: 4px solid #059669;">
                        <p style="color: #065f46; font-size: 13px; margin: 0 0 8px 0; font-weight: 600;">ENTREGUES</p>
                        <p style="color: #047857; font-size: 24px; font-weight: 700; margin: 0;">{{ $deliveredOrders }}</p>
                    </div>
                </div>
            </div>

            <!-- Categorias -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">Categorias</h3>
                
                @foreach($categories as $category)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                        <span style="color: #6b7280; font-size: 14px;">{{ $category->category }}</span>
                        <span style="background-color: #3b82f6; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $category->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Produtos Mais Vendidos -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">Top 5 Produtos Vendidos</h3>
                
                @forelse($topProducts as $index => $product)
                    <div style="display: flex; align-items: center; padding: 16px 0; border-bottom: 1px solid #e5e7eb;">
                        <div style="width: 40px; height: 40px; background-color: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 16px; font-weight: 700; color: #3b82f6;">
                            {{ $index + 1 }}
                        </div>
                        <div style="flex: 1;">
                            <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $product->product_name }}</p>
                            <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 13px;">{{ $product->total_quantity }} unidades vendidas</p>
                        </div>
                        <span style="background-color: #f0fdf4; color: #15803d; padding: 6px 12px; border-radius: 4px; font-weight: 600; font-size: 13px;">
                            {{ $product->sales_count }} vendas
                        </span>
                    </div>
                @empty
                    <p style="color: #6b7280; text-align: center; padding: 40px 0;">Nenhuma venda registrada</p>
                @endforelse
            </div>

            <!-- Últimos Pedidos -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">Últimos Pedidos</h3>
                
                @forelse($recentOrders as $order)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid #e5e7eb;">
                        <div>
                            <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $order->order_number }}</p>
                            <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 13px;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                            <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 13px;">
                                @switch($order->status)
                                    @case('pending')
                                        <span style="background-color: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Pendente</span>
                                    @break
                                    @case('processing')
                                        <span style="background-color: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Processando</span>
                                    @break
                                    @case('shipped')
                                        <span style="background-color: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Enviado</span>
                                    @break
                                    @case('delivered')
                                        <span style="background-color: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Entregue</span>
                                    @break
                                    @case('cancelled')
                                        <span style="background-color: #fee2e2; color: #7f1d1d; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Cancelado</span>
                                    @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                @empty
                    <p style="color: #6b7280; text-align: center; padding: 40px 0;">Nenhum pedido registrado</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

</script>
@endsection