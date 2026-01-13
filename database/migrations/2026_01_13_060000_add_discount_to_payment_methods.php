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
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('fee_fixed')
                ->comment('Desconto percentual para esta forma de pagamento (ex: 5 para 5%)');
            $table->decimal('discount_fixed', 8, 2)->default(0)->after('discount_percentage')
                ->comment('Desconto fixo em reais para esta forma de pagamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'discount_fixed']);
        });
    }
};
