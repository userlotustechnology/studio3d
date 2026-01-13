<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Model;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Criar configuração de cashback
        Model::unguard();
        
        $setting = new \App\Models\Setting();
        $setting->key = 'cashback_percentage';
        $setting->value = '5.00';
        $setting->description = 'Percentual de cashback concedido nas compras (ex: 5.00 = 5%)';
        $setting->type = 'decimal';
        $setting->save();
        
        Model::reguard();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Setting::where('key', 'cashback_percentage')->delete();
    }
};
