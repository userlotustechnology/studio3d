<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageUploadTest extends TestCase
{
    use RefreshDatabase;
    public function test_store_product_with_multiple_images_stores_files_and_records()
    {
        $this->markTestSkipped('Skipping feature test; enable when test DB migrations support altering tables on sqlite.');
        Storage::fake('public');

        $this->withoutMiddleware();

        $category = Category::create(['name' => 'Teste', 'description' => null, 'is_active' => true]);

        $files = [
            UploadedFile::fake()->image('one.jpg'),
            UploadedFile::fake()->image('two.jpg'),
        ];

        $response = $this->post(route('admin.products.store'), [
            'name' => 'Produto Teste',
            'description' => 'Desc',
            'price' => '10.00',
            'category_id' => $category->id,
            'type' => 'physical',
            'stock' => 5,
            'images' => $files,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Produto Teste')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->images);

        foreach ($product->images as $img) {
            Storage::disk('public')->assertExists($img->path);
        }
    }
}
