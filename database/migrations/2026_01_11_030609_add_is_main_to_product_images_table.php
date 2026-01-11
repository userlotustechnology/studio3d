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
        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_main')->default(false)->after('position');
        });

        // Set the first image of each product as main if none is set
        $products = \App\Models\Product::has('images')->get();
        foreach ($products as $product) {
            $firstImage = $product->images()->orderBy('position')->orderBy('id')->first();
            if ($firstImage) {
                $firstImage->update(['is_main' => true]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('is_main');
        });
    }
};
