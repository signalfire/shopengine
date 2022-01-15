<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Basket;
use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class BasketControllerTest extends TestCase
{
    /** @test */
    public function itCreatesAndReturnsBasket()
    {
        $this
            ->post('/api/basket')
            ->assertStatus(201);
        $this->assertDatabaseCount('baskets', 1);
    }

    /** @test */
    public function itFailsToRetrieveBasketNonUuid()
    {
        $this
            ->get('/api/basket/12')
            ->assertStatus(404);
    }

    /** @test */
    public function itFailsToRetrieveBasketNonExisting()
    {
        $uuid = (string) Str::uuid();
        $this
            ->get('/api/basket/'.$uuid)
            ->assertStatus(404);
    }

    /** @test */
    public function itRetrievesExistingBasket()
    {
        $basket = Basket::factory()->create();
        $this
            ->get('/api/basket/'.$basket->id)
            ->assertJson([
                'basket' => [
                    'id' => $basket->id,
                ],
            ])
            ->assertStatus(200);
    }

    /** @test */
    public function itRetrievesExistingBasketWithItems()
    {
        $basket = Basket::factory()->create();
        $products = Product::factory()->count(3)->create();
        foreach ($products as $product) {
            $variant = ProductVariant::factory()->state([
                'product_id' => $product->id,
            ])->create();
            BasketItem::factory()
                ->state([
                    'basket_id'          => $basket->id,
                    'product_variant_id' => $variant->id,
                ])
                ->create();
        }
        $this
            ->get('/api/basket/'.$basket->id)
            ->assertJsonStructure([
                'basket' => [
                    'id',
                    'created_at',
                    'updated_at',
                    'items',
                ],
            ])
            ->assertJsonCount(3, 'basket.items')
            ->assertStatus(200);
    }

    /** @test */
    public function itFailsToDestroyBasketNonExisting()
    {
        $this
            ->delete('/api/basket/'.(string) Str::uuid())
            ->assertStatus(404);
    }

    /** @test */
    public function itFailsToDestroyBasketNonUuid()
    {
        $this
            ->delete('/api/basket/12')
            ->assertStatus(404);
    }

    /** @test */
    public function itDestroysBasket()
    {
        $basket = Basket::factory()->create();
        $this
            ->delete('/api/basket/'.$basket->id)
            ->assertJsonStructure([
                'basket' => [
                    'id',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertStatus(202);

        $this->assertDeleted($basket);
    }
}
