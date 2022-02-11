<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
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
            ->json('POST', route('basket.item.store', ['basket' => $basket->id]), [])
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddingToBasketVariantIdMissing()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                route('basket.item.store', ['basket' => $basket->id]),
                ['quantity' => 1]
            )
            ->assertJsonValidationErrorFor('product_variant_id', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddingToBasketQuantityMissing()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                route('basket.item.store', ['basket' => $basket->id]),
                ['product_variant_id' => (string) Str::uuid()]
            )
            ->assertJsonValidationErrorFor('quantity', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddingToBasketQuantityNotInteger()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                route('basket.item.store', ['basket' => $basket->id]),
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
                route('basket.item.store', ['basket' => $basket->id]),
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
        $this
            ->json(
                'POST',
                route('basket.item.store', ['basket' => (string) Str::uuid()]),
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 1,
                ]
            )
            ->assertStatus(404);
    }

    public function testFailsAddingToBasketProductVariantNotFound()
    {
        $basket = Basket::factory()->create();

        $this
            ->json(
                'POST',
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => (string) Str::uuid(),
                    'quantity'           => 1,
                ]
            )
            ->assertStatus(404);
    }

    public function testFailsAddingToBasketProductVariantNoStock()
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
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            )
            ->assertStatus(404);
    }

    public function testFailsAddingToBasketQuantityHigherVariantStock()
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
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 2,
                ]
            )
            ->assertStatus(404);
    }

    public function testFailsAddingToBasketVariantStatusNotAvailable()
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
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            )
            ->assertStatus(404);
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
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 1,
                ]
            )
            ->assertJsonCount(1, 'data.items')
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
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 2,
                ]
            )
            ->assertJsonCount(1, 'data.items')
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
                route('basket.item.store', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                    'quantity'           => 0,
                ]
            )
            ->assertJsonCount(0, 'data.items')
            ->assertStatus(201);

        $this->assertDatabaseCount('basket_items', 0);
    }

    public function testFailsToDeleteItemFromNonExistantBasket()
    {
        $this
            ->json(
                'DELETE',
                route('basket.item.destroy', (string) Str::uuid()),
                [
                    'product_variant_id' => (string) Str::uuid(),
                ]
            )
            ->assertStatus(404);
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
                route('basket.item.destroy', ['basket' => $basket->id]),
                [
                    'product_variant_id' => $variant->id,
                ]
            )
            ->assertJsonCount(0, 'data.items')
            ->assertStatus(202);

        $this->assertDatabaseCount('basket_items', 0);
    }
}
