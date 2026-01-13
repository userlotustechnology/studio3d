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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjustment'])->comment('Tipo: entrada, saída ou ajuste');
            $table->integer('quantity')->comment('Quantidade movimentada (positivo para entrada, negativo para saída)');
            $table->integer('stock_before')->comment('Estoque antes da movimentação');
            $table->integer('stock_after')->comment('Estoque após a movimentação');
            $table->string('reason')->comment('Motivo da movimentação');
            $table->string('user_name')->nullable()->comment('Nome do usuário responsável');
            $table->timestamps();
            
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
