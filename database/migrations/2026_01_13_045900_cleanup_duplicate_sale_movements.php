<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remover registros duplicados do tipo 'sale' que foram criados erroneamente
        // Esses registros não alteravam o estoque real (stock_before = stock_after)
        // e são redundantes com as cart_reservation já existentes
        
        $deletedCount = DB::table('stock_movements')
            ->where('type', 'sale')
            ->where('stock_before', DB::raw('stock_after')) // Confirma que não houve mudança real
            ->delete();
        
        if ($deletedCount > 0) {
            \Log::info("Removidos {$deletedCount} registros duplicados do tipo 'sale' da tabela stock_movements");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não há como recuperar os registros deletados
        // Mas eles eram redundantes, então não há problema
    }
};
