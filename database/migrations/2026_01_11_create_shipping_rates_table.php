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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('state_code', 2)->unique(); // UF (SP, RJ, MG, etc)
            $table->string('state_name')->nullable(); // Nome do estado
            $table->decimal('rate', 8, 2); // Valor do frete
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Inserir valores padrão de frete por estado
        DB::table('shipping_rates')->insert([
            ['state_code' => 'SP', 'state_name' => 'São Paulo', 'rate' => 15.00, 'is_active' => true],
            ['state_code' => 'RJ', 'state_name' => 'Rio de Janeiro', 'rate' => 18.00, 'is_active' => true],
            ['state_code' => 'MG', 'state_name' => 'Minas Gerais', 'rate' => 19.00, 'is_active' => true],
            ['state_code' => 'PR', 'state_name' => 'Paraná', 'rate' => 20.00, 'is_active' => true],
            ['state_code' => 'BA', 'state_name' => 'Bahia', 'rate' => 22.00, 'is_active' => true],
            ['state_code' => 'SC', 'state_name' => 'Santa Catarina', 'rate' => 24.00, 'is_active' => true],
            ['state_code' => 'RS', 'state_name' => 'Rio Grande do Sul', 'rate' => 25.00, 'is_active' => true],
            ['state_code' => 'GO', 'state_name' => 'Goiás', 'rate' => 23.00, 'is_active' => true],
            ['state_code' => 'DF', 'state_name' => 'Distrito Federal', 'rate' => 23.00, 'is_active' => true],
            ['state_code' => 'MS', 'state_name' => 'Mato Grosso do Sul', 'rate' => 24.00, 'is_active' => true],
            ['state_code' => 'MT', 'state_name' => 'Mato Grosso', 'rate' => 28.00, 'is_active' => true],
            ['state_code' => 'PE', 'state_name' => 'Pernambuco', 'rate' => 26.00, 'is_active' => true],
            ['state_code' => 'CE', 'state_name' => 'Ceará', 'rate' => 26.00, 'is_active' => true],
            ['state_code' => 'PA', 'state_name' => 'Pará', 'rate' => 30.00, 'is_active' => true],
            ['state_code' => 'AM', 'state_name' => 'Amazonas', 'rate' => 35.00, 'is_active' => true],
            ['state_code' => 'ES', 'state_name' => 'Espírito Santo', 'rate' => 17.00, 'is_active' => true],
            ['state_code' => 'PB', 'state_name' => 'Paraíba', 'rate' => 26.00, 'is_active' => true],
            ['state_code' => 'RN', 'state_name' => 'Rio Grande do Norte', 'rate' => 27.00, 'is_active' => true],
            ['state_code' => 'AL', 'state_name' => 'Alagoas', 'rate' => 26.00, 'is_active' => true],
            ['state_code' => 'SE', 'state_name' => 'Sergipe', 'rate' => 26.00, 'is_active' => true],
            ['state_code' => 'PI', 'state_name' => 'Piauí', 'rate' => 27.00, 'is_active' => true],
            ['state_code' => 'MA', 'state_name' => 'Maranhão', 'rate' => 27.00, 'is_active' => true],
            ['state_code' => 'TO', 'state_name' => 'Tocantins', 'rate' => 32.00, 'is_active' => true],
            ['state_code' => 'AC', 'state_name' => 'Acre', 'rate' => 38.00, 'is_active' => true],
            ['state_code' => 'RO', 'state_name' => 'Rondônia', 'rate' => 34.00, 'is_active' => true],
            ['state_code' => 'RR', 'state_name' => 'Roraima', 'rate' => 40.00, 'is_active' => true],
            ['state_code' => 'AP', 'state_name' => 'Amapá', 'rate' => 38.00, 'is_active' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
