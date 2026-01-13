<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar o enum para incluir novos tipos
        DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `type` ENUM('in', 'out', 'adjustment', 'cart_reservation', 'cart_return', 'sale') NOT NULL COMMENT 'Tipo: entrada, saída, ajuste, reserva do carrinho, retorno do carrinho ou venda'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter para os tipos originais
        DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `type` ENUM('in', 'out', 'adjustment') NOT NULL COMMENT 'Tipo: entrada, saída ou ajuste'");
    }
};
