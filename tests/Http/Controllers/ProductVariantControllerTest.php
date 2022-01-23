<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class ProductVariantControllerTest extends TestCase
{
    public function testGetsProductVariants()
    {
        $product = Product::factory()->create();
        $variants = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->count(3)->create();
        $this->json('GET', '/api/product/'.$product->id.'/variants')
            ->assertJsonCount(3, 'data')
            ->assertStatus(200);
    }

    public function testGetProductVariant()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->create();
        $this->json('GET', '/api/product/'.$product->id.'/variant/'.$variant->id)
            ->assertJson([
                'data' => [
                    'id'         => $variant->id,
                    'product_id' => $variant->product_id,
                    'name'       => $variant->name,
                    'slug'       => $variant->slug,
                    'stock'      => $variant->stock,
                    'price'      => $variant->price,
                    'status'     => $variant->status,
                ],
            ])
            ->assertStatus(200);
    }
}
