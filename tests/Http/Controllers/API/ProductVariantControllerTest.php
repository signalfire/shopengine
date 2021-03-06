<?php

namespace Signalfire\Shopengine\Tests;

use Laravel\Sanctum\Sanctum;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class ProductVariantControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $this->user->roles()->attach($this->role);
    }

    public function testGetsProductVariants()
    {
        $product = Product::factory()->create();
        $variants = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->count(3)->create();
        $this->json('GET', route('product.variants.index', ['product' => $product->id]))
            ->assertJsonCount(3, 'data')
            ->assertStatus(200);
    }

    public function testGetProductVariant()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->create();
        $this->json('GET', route('product.variant.show', ['product' => $product->id, 'variant' => $variant->id]))
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

    public function testCreateProductVariant()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $this
            ->json('POST', route('product.variant.store', ['product' => $product->id]), [
                'product_id'  => $product->id,
                'barcode'     => '9780201379621',
                'name'        => 'test',
                'slug'        => 'test',
                'stock'       => 10,
                'price'       => 1.99,
                'length'      => 10,
                'width'       => 11,
                'height'      => 12,
                'weight'      => 1.2,
                'status'      => 1,
            ])
            ->assertJson([
                'data' => [
                    'product_id'  => $product->id,
                    'barcode'     => '9780201379621',
                    'name'        => 'test',
                    'slug'        => 'test',
                    'stock'       => 10,
                    'price'       => 1.99,
                    'length'      => 10,
                    'width'       => 11,
                    'height'      => 12,
                    'weight'      => 1.2,
                    'status'      => 1,
                ],
            ])
            ->assertStatus(201);
    }

    public function testUpdateProductVariant()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->create();

        $this
            ->json('PUT', route('product.variant.update', ['product' => $product->id, 'variant' => $variant->id]), [
                'product_id'  => $product->id,
                'name'        => 'test1',
                'slug'        => 'test2',
                'stock'       => 12,
                'price'       => 299,
                'length'      => 5,
                'width'       => 6,
                'height'      => 7,
                'weight'      => 1.5,
                'status'      => 1,
            ])
            ->assertStatus(204);

        $this->assertDatabaseCount('product_variants', 1);

        $this->assertDatabaseHas('product_variants', [
            'id'          => $variant->id,
            'product_id'  => $product->id,
            'name'        => 'test1',
            'slug'        => 'test2',
            'stock'       => 12,
            'price'       => 299,
            'length'      => 5,
            'width'       => 6,
            'height'      => 7,
            'weight'      => 1.5,
            'status'      => 1,
        ]);
    }
}
