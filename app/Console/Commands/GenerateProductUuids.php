<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateProductUuids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate-uuids';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate UUIDs for products that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = 0;
        
        Product::whereNull('uuid')->each(function ($product) use (&$count) {
            $product->update(['uuid' => Str::uuid()]);
            $count++;
        });

        if ($count > 0) {
            $this->info("✓ {$count} UUID(s) generated successfully!");
        } else {
            $this->info('✓ All products already have UUIDs.');
        }
    }
}
