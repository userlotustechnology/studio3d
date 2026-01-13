<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona índices importantes para otimizar queries do dashboard e relatórios
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Índice composto para queries de receita por período
            $table->index(['is_draft', 'created_at'], 'orders_draft_created_idx');
            
            // Índice composto para queries de status + draft
            $table->index(['is_draft', 'status'], 'orders_draft_status_idx');
            
            // Índice para customer_id se existir
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->index('customer_id', 'orders_customer_idx');
            }
            
            // Índice para payment_method_id se existir
            if (Schema::hasColumn('orders', 'payment_method_id')) {
                $table->index('payment_method_id', 'orders_payment_method_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Índice composto para joins frequentes
            $table->index(['order_id', 'product_id'], 'order_items_order_product_idx');
            
            // Índice para product_name (top produtos)
            $table->index('product_name', 'order_items_product_name_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            // Índice composto para produtos ativos
            $table->index(['is_active', 'category_id'], 'products_active_category_idx');
            
            // Índice para estoque baixo
            $table->index(['is_active', 'stock'], 'products_active_stock_idx');
            
            // Índice para nome (buscas)
            $table->index('name', 'products_name_idx');
        });

        Schema::table('customers', function (Blueprint $table) {
            // Índice para email (buscas e joins)
            if (!Schema::hasIndex('customers', ['email'])) {
                $table->index('email', 'customers_email_idx');
            }
            
            // Índice para created_at (novos clientes)
            $table->index('created_at', 'customers_created_idx');
        });

        // Adicionar índice fulltext para busca de produtos (se MySQL 5.7+)
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE products ADD FULLTEXT INDEX products_search_idx (name, description)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_draft_created_idx');
            $table->dropIndex('orders_draft_status_idx');
            
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->dropIndex('orders_customer_idx');
            }
            
            if (Schema::hasColumn('orders', 'payment_method_id')) {
                $table->dropIndex('orders_payment_method_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_product_idx');
            $table->dropIndex('order_items_product_name_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_category_idx');
            $table->dropIndex('products_active_stock_idx');
            $table->dropIndex('products_name_idx');
            
            if (config('database.default') === 'mysql') {
                DB::statement('ALTER TABLE products DROP INDEX products_search_idx');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_email_idx');
            $table->dropIndex('customers_created_idx');
        });
    }
};
