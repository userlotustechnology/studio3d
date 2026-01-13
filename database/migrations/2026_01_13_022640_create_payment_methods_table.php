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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome da forma de pagamento');
            $table->string('code')->unique()->comment('Código único da forma de pagamento');
            $table->text('description')->nullable()->comment('Descrição da forma de pagamento');
            $table->decimal('fee_percentage', 5, 2)->default(0)->comment('Taxa percentual (ex: 3.5 para 3,5%)');
            $table->decimal('fee_fixed', 8, 2)->default(0)->comment('Taxa fixa em reais');
            $table->integer('settlement_days')->default(0)->comment('Dias para compensação');
            $table->boolean('is_active')->default(true)->comment('Se a forma de pagamento está ativa');
            $table->timestamps();
            
            $table->index(['is_active', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
