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
        // Adicionar 'draft' ao enum da tabela orders
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'draft'");
        
        // Adicionar 'draft' ao enum da tabela order_status_histories
        DB::statement("ALTER TABLE `order_status_histories` MODIFY COLUMN `from_status` ENUM('draft', 'pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL");
        DB::statement("ALTER TABLE `order_status_histories` MODIFY COLUMN `to_status` ENUM('draft', 'pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover 'draft' do enum da tabela orders
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending'");
        
        // Remover 'draft' do enum da tabela order_status_histories  
        DB::statement("ALTER TABLE `order_status_histories` MODIFY COLUMN `from_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL");
        DB::statement("ALTER TABLE `order_status_histories` MODIFY COLUMN `to_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL");
    }
};
