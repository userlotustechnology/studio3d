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
        Schema::create('shipping_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ex: Sedex, PAC, Jadlog
            $table->string('slug')->unique(); // Ex: sedex, pac, jadlog
            $table->string('code')->unique()->nullable(); // Código da empresa
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('tracking_url_template')->nullable(); // URL template: https://www.correios.com.br/rastreamento?codigo={code}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_companies');
    }
};
