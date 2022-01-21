<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $this->expectException(HttpException::class);
        $this
            ->withoutExceptionHandling()
            ->get('/api/basket/'.(string) Str::uuid());
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
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'created_at',
                    'updated_at',
                    'items',
                ],
            ])
            ->assertJsonCount(3, 'data.items')
            ->assertStatus(200);
    }

    public function testFailsToDestroyBasketNonExisting()
    {
        $this->expectException(HttpException::class);

        $this
            ->withoutExceptionHandling()
            ->delete('/api/basket/'.(string) Str::uuid());
    }

    public function testFailsToDestroyBasketNonUuid()
    {
        // Checking for 405 as laravel fallback only seems to work on GET, HEAD, OPTIONS
        // anything else seems to return 405.
        $this
            ->delete('/api/basket/12')
            ->assertStatus(405);
    }

    public function testDestroysBasket()
    {
        $basket = Basket::factory()->create();
        $this
            ->delete('/api/basket/'.$basket->id)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertStatus(202);

        $this->assertDeleted($basket);
    }
}
