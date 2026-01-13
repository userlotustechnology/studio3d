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
        Schema::create('cash_book', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['credit', 'debit'])->comment('Tipo: crédito ou débito');
            $table->string('category')->comment('Categoria: sale, shipping, fee, refund, etc');
            $table->decimal('amount', 10, 2)->comment('Valor da movimentação');
            $table->decimal('fee_amount', 10, 2)->default(0)->comment('Valor da taxa (para débitos)');
            $table->decimal('net_amount', 10, 2)->comment('Valor líquido (amount - fee_amount)');
            $table->string('description')->comment('Descrição da movimentação');
            $table->json('metadata')->nullable()->comment('Dados adicionais em JSON');
            $table->date('transaction_date')->comment('Data da transação');
            $table->date('settlement_date')->nullable()->comment('Data de compensação/liquidação');
            $table->string('reference')->nullable()->comment('Referência externa (ID do gateway, etc)');
            $table->timestamps();
            
            $table->index(['type', 'transaction_date']);
            $table->index(['category', 'transaction_date']);
            $table->index(['settlement_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_book');
    }
};
