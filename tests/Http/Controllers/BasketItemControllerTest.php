<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Basket;
use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class BasketItemControllerTest extends TestCase
{
    /** @test */
    public function itFailsAddingToBasket()
    {
        $basket = Basket::factory()->create();

        $this
            ->json('POST', '/api/basket/'.$basket->id.'/items', [])
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsAddingToBasketVariantIdMissing()
    {
        $basket = Basket::factory()->create();

        $this
            ->json('POST', '/api/basket/'.$basket->id.'/items', ['quantity' => 1])
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsAddingToBasketQuantityMissing()
    {
        $basket = Basket::factory()->create();

        $this
            ->json('POST', '/api/basket/'.$basket->id.'/items', ['product_variant_id' => (string) Str::uuid()])
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsAddingToBasketQuantityNotInteger()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 'A',
                ]
            )
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsAddingToBasketProductVariantNotUuid()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => 12,
                    'quantity'           => 1,
                ]
            )
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsAddingToBasketNoBasket()
    {
        $this
            ->json(
                'POST',
                '/api/basket/'.(string) Str::uuid().'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 1,
                ]
            )
            ->assertJson(['error' => __('Unable to find basket')])
            ->assertStatus(404);
    }

    /** @test */
    public function itFailsAddingToBasketProductVariantNotFound()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 1,
                ]
            )
            ->assertJson(['error' => __('Variant not available or insufficient stock')])
            ->assertStatus(404);
    }

    /** @test */
    public function itFailsAddingToBasketProductVariantNoStock()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 0,
        ])->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            )
            ->assertJson(['error' => __('Variant not available or insufficient stock')])
            ->assertStatus(404);
    }

    /** @test */
    public function itFailsAddingToBasketQuantityHigherVariantStock()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 1,
        ])->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 2,
                ]
            )
            ->assertJson(['error' => __('Variant not available or insufficient stock')])
            ->assertStatus(404);
    }

    /** @test */
    public function itFailsAddingToBasketVariantStatusNotAvailable()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 10,
            'status'     => (int) config('shopengine.variant.status.UNAVAILABLE'),
        ])->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            )
            ->assertJson(['error' => __('Variant not available or insufficient stock')])
            ->assertStatus(404);
    }

    /** @test */
    public function itAddsNewItemToBasket()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 10,
            'status'     => (int) config('shopengine.variant.status.AVAILABLE'),
        ])->create();

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            )
            ->assertJsonStructure([
                'basket' => [
                    'id', 'created_at', 'updated_at', 'items',
                ],
            ])
            ->assertJsonCount(1, 'basket.items')
            ->assertStatus(201);

        $this->assertDatabaseCount('basket_items', 1);
    }

    /** @test */
    public function itUpdatesItemInBasket()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 10,
            'status'     => (int) config('shopengine.variant.status.AVAILABLE'),
        ])->create();

        $basket_item = BasketItem::factory()->state([
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
        ])->create();

        $this->assertDatabaseHas('basket_items', [
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
        ]);

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 2,
                ]
            )
            ->assertJsonStructure([
                'basket' => [
                    'id', 'created_at', 'updated_at', 'items',
                ],
            ])
            ->assertJsonCount(1, 'basket.items')
            ->assertStatus(201);

        $this->assertDatabaseCount('basket_items', 1);

        $this->assertDatabaseHas('basket_items', [
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 2,
        ]);
    }

    /** @test */
    public function itRemovesItemWithZeroQuantityFromBasket()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 10,
            'status'     => (int) config('shopengine.variant.status.AVAILABLE'),
        ])->create();

        $basket_item = BasketItem::factory()->state([
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
        ])->create();

        $this->assertDatabaseHas('basket_items', [
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
        ]);

        $this
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 0,
                ]
            )
            ->assertJsonStructure([
                'basket' => [
                    'id', 'created_at', 'updated_at', 'items',
                ],
            ])
            ->assertJsonCount(0, 'basket.items')
            ->assertStatus(201);

        $this->assertDatabaseCount('basket_items', 0);
    }

    /** @test */
    public function itFailsToDeleteItemFromNonExistantBasket()
    {
        $this
            ->json(
                'DELETE',
                '/api/basket/'.(string) Str::uuid().'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                ]
            )
            ->assertStatus(404);
    }

    /** @test */
    public function itDeletesItemFromBasket()
    {
        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 10,
            'status'     => (int) config('shopengine.variant.status.AVAILABLE'),
        ])->create();

        $basket_item = BasketItem::factory()->state([
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
        ])->create();

        $this->assertDatabaseHas('basket_items', [
            'basket_id'          => $basket->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 1,
        ]);

        $this
            ->json(
                'DELETE',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                ]
            )
            ->assertJsonStructure([
                'basket' => [
                    'id', 'created_at', 'updated_at', 'items',
                ],
            ])
            ->assertJsonCount(0, 'basket.items')
            ->assertStatus(202);

        $this->assertDatabaseCount('basket_items', 0);
    }
}
