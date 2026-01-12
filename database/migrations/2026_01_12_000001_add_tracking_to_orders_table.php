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
            // Adicionar tracking_code e shipping_company_id
            if (!Schema::hasColumn('orders', 'tracking_code')) {
                $table->string('tracking_code')->nullable()->after('shipping_cost');
            }
            if (!Schema::hasColumn('orders', 'shipping_company_id')) {
                $table->foreignId('shipping_company_id')->nullable()->after('tracking_code')->constrained('shipping_companies')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_company_id')) {
                $table->dropForeignKeyIfExists(['shipping_company_id']);
                $table->dropColumn('shipping_company_id');
            }
            if (Schema::hasColumn('orders', 'tracking_code')) {
                $table->dropColumn('tracking_code');
            }
        });
    }
};
