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
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('reason')->constrained('users')->onDelete('set null');
            $table->index('user_id');
            // Alterar 'changed_by' para string ou remover após migração de dados
            $table->renameColumn('changed_by', 'changed_by_legacy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->renameColumn('changed_by_legacy', 'changed_by');
        });
    }
};
