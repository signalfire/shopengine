<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Basket;
use Signalfire\Shopengine\Models\BasketItem;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class BasketControllerTest extends TestCase
{
    public function testCreatesAndReturnsBasket()
    {
        $this->assertDatabaseCount('baskets', 0);
        $this
            ->post('/api/basket')
            ->assertStatus(201);
        $this->assertDatabaseCount('baskets', 1);
    }

    public function testFailsToRetrieveBasketNonUuid()
    {
        $this
            ->json('GET', '/api/basket/12')
            ->assertStatus(404);
    }

    public function testFailsToRetrieveBasketNonExisting()
    {
        $this
            ->get('/api/basket/'.(string) Str::uuid())
            ->assertStatus(404);
    }

    public function testRetrievesExistingBasket()
    {
        $basket = Basket::factory()->create();
        $this
            ->get('/api/basket/'.$basket->id)
            ->assertJson([
                'data' => [
                    'id' => $basket->id,
                ],
            ])
            ->assertStatus(200);
    }

    public function testRetrievesExistingBasketWithItems()
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
            ->assertJsonCount(3, 'data.items')
            ->assertStatus(200);
    }

    public function testFailsToDestroyBasketNonExisting()
    {
        $this
            ->delete('/api/basket/'.(string) Str::uuid())
            ->assertStatus(404);
    }

    public function testFailsToDestroyBasketNonUuid()
    {
        $this
            ->delete('/api/basket/12')
            ->assertStatus(404);
    }

    public function testDestroysBasket()
    {
        $basket = Basket::factory()->create();
        $this
            ->delete('/api/basket/'.$basket->id)
            ->assertStatus(202);

        $this->assertDeleted($basket);
    }
}
