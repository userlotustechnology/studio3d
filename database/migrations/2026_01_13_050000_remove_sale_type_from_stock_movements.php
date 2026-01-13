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
        // Remover o tipo 'sale' do ENUM pois não é mais necessário
        // O estoque já é decrementado na cart_reservation, não precisa de uma segunda movimentação
        DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `type` ENUM('in', 'out', 'adjustment', 'cart_reservation', 'cart_return') NOT NULL COMMENT 'Tipo: entrada, saída, ajuste, reserva do carrinho ou retorno do carrinho'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar o tipo 'sale' caso precise fazer rollback
        DB::statement("ALTER TABLE `stock_movements` MODIFY COLUMN `type` ENUM('in', 'out', 'adjustment', 'cart_reservation', 'cart_return', 'sale') NOT NULL COMMENT 'Tipo: entrada, saída, ajuste, reserva do carrinho, retorno do carrinho ou venda'");
    }
};
