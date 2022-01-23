<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Product;

class ProductControllerTest extends TestCase
{
    public function testGetProductById()
    {
        $product = Product::factory()->create();

        $this
            ->json('GET', '/api/product/'.$product->id)
            ->assertStatus(200);
    }

    public function testGetProductBySlug()
    {
        $product = Product::factory()->create();

        $this
            ->json('GET', '/api/product/'.$product->slug)
            ->assertStatus(200);
    }

    public function testFailsGetProductByIdMissing()
    {
        $this
            ->json('GET', '/api/product/'.(string) Str::uuid())
            ->assertStatus(404);
    }

    public function testFailsGetProductBySlugMissing()
    {
        $this
            ->json('GET', '/api/product/test')
            ->assertStatus(404);
    }
}
