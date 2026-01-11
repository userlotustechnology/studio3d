<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class ImageUrlTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_product_image_url_local_uses_public_disk()
    {
        $mock = Mockery::mock();
        $mock->shouldReceive('url')->with('products/test.jpg')->andReturn('http://local/storage/products/test.jpg');
        Storage::shouldReceive('disk')->with('public')->andReturn($mock);

        $this->app['env'] = 'local';
        $this->app['config']->set('app.env', 'local');

        $p = new Product(['image' => 'products/test.jpg']);

        $this->assertSame('http://local/storage/products/test.jpg', $p->image_url);
    }

    public function test_product_image_url_production_uses_temporary_url()
    {
        $mock = Mockery::mock();
        $mock->shouldReceive('temporaryUrl')
            ->with('products/test.jpg', Mockery::on(function ($value) {
                return $value instanceof \DateTimeInterface;
            }))
            ->andReturn('https://s3-signed-url');

        Storage::shouldReceive('disk')->with('s3')->andReturn($mock);

        $this->app['env'] = 'production';
        $this->app['config']->set('app.env', 'production');

        $p = new Product(['image' => 'products/test.jpg']);

        $this->assertSame('https://s3-signed-url', $p->image_url);
    }

    public function test_product_image_url_production_fallback_to_public_url()
    {
        $mock = Mockery::mock();
        $mock->shouldReceive('temporaryUrl')->andThrow(new \Exception('no temp'));
        $mock->shouldReceive('url')->with('products/test.jpg')->andReturn('https://s3-public-url');

        Storage::shouldReceive('disk')->with('s3')->andReturn($mock);

        $this->app['env'] = 'production';
        $this->app['config']->set('app.env', 'production');

        $p = new Product(['image' => 'products/test.jpg']);

        $this->assertSame('https://s3-public-url', $p->image_url);
    }
}
