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
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('order_id')->constrained('users')->onDelete('set null');
            $table->index('user_id');
            // Manter user_name como legacy para auditoria histórica
            $table->renameColumn('user_name', 'user_name_legacy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->renameColumn('user_name_legacy', 'user_name');
        });
    }
};
