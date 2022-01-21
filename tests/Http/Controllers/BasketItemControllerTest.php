<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Signalfire\Shopengine\Models\Basket;
use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class BasketItemControllerTest extends TestCase
{
    public function testFailsAddingToBasket()
    {
        $basket = Basket::factory()->create();

        $this
            ->json('POST', '/api/basket/'.$basket->id.'/items', [])
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddingToBasketVariantIdMissing()
    {
        $basket = Basket::factory()->create();

        $this
            ->json('POST', '/api/basket/'.$basket->id.'/items', ['quantity' => 1])
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddingToBasketQuantityMissing()
    {
        $basket = Basket::factory()->create();

        $this
            ->json('POST', '/api/basket/'.$basket->id.'/items', ['product_variant_id' => (string) Str::uuid()])
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddingToBasketQuantityNotInteger()
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

    public function testFailsAddingToBasketProductVariantNotUuid()
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

    public function testFailsAddingToBasketNoBasket()
    {
        $this->expectException(HttpException::class);

        $this
            ->withoutExceptionHandling()
            ->json(
                'POST',
                '/api/basket/'.(string) Str::uuid().'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 1,
                ]
            );
    }

    public function testFailsAddingToBasketProductVariantNotFound()
    {
        $this->expectException(HttpException::class);

        $basket = Basket::factory()->create();

        $this
            ->withoutExceptionHandling()
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 1,
                ]
            );
    }

    public function testFailsAddingToBasketProductVariantNoStock()
    {
        $this->expectException(HttpException::class);

        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 0,
        ])->create();

        $this
            ->withoutExceptionHandling()
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            );
    }

    public function testFailsAddingToBasketQuantityHigherVariantStock()
    {
        $this->expectException(HttpException::class);

        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 1,
        ])->create();

        $this
            ->withoutExceptionHandling()
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 2,
                ]
            );
    }

    public function testFailsAddingToBasketVariantStatusNotAvailable()
    {
        $this->expectException(HttpException::class);

        $basket = Basket::factory()->create();

        $product = Product::factory()->create();

        $variant = ProductVariant::factory()->state([
            'product_id' => $product->id,
            'stock'      => 10,
            'status'     => (int) config('shopengine.variant.status.UNAVAILABLE'),
        ])->create();

        $this
            ->withoutExceptionHandling()
            ->json(
                'POST',
                '/api/basket/'.$basket->id.'/items',
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            );
    }

    public function testAddsNewItemToBasket()
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

    public function testUpdatesItemInBasket()
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

    public function testRemovesItemWithZeroQuantityFromBasket()
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

    public function testFailsToDeleteItemFromNonExistantBasket()
    {
        $this->expectException(HttpException::class);

        $this
            ->withoutExceptionHandling()
            ->json(
                'DELETE',
                '/api/basket/'.(string) Str::uuid().'/items',
                [
                    'product_variant_id' => (string) Str::uuid(),
                ]
            );
    }

    public function testDeletesItemFromBasket()
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
