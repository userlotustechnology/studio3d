<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->comment('UUID único do pedido para URLs públicas');
        });
        
        // Gerar UUIDs para pedidos existentes
        $orders = \App\Models\Order::whereNull('uuid')->get();
        foreach ($orders as $order) {
            $order->uuid = \Illuminate\Support\Str::uuid();
            $order->save();
        }
        
        // Agora adicionar o índice único
        Schema::table('orders', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
