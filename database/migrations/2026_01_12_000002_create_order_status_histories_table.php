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
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('from_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->enum('to_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->text('reason')->nullable();
            $table->string('changed_by')->nullable(); // 'system', 'admin', 'command'
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
