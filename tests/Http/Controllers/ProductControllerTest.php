<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Product;

class ProductControllerTest extends TestCase
{
    public function testGetProducts()
    {
        $products = Product::factory()->create();
        $this
            ->json('GET', '/api/products')
            ->assertJsonCount(1, 'products')
            ->assertJsonPath('total', 1)
            ->assertJsonPath('pages', 1)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedNoParams()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', '/api/products')
            ->assertJsonCount(10, 'products')
            ->assertJsonPath('products.0.id', $products[0]->id)
            ->assertJsonPath('products.9.id', $products[9]->id)
            ->assertJsonPath('total', 20)
            ->assertJsonPath('pages', 2)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedPageParams()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', '/api/products?page=2')
            ->assertJsonCount(10, 'products')
            ->assertJsonPath('products.0.id', $products[10]->id)
            ->assertJsonPath('products.9.id', $products[19]->id)
            ->assertJsonPath('total', 20)
            ->assertJsonPath('pages', 2)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedChangePageSize()
    {
        $products = Product::factory()->count(10)->create();
        $this
            ->json('GET', '/api/products?size=5')
            ->assertJsonCount(5, 'products')
            ->assertJsonPath('products.0.id', $products[0]->id)
            ->assertJsonPath('products.4.id', $products[4]->id)
            ->assertJsonPath('total', 10)
            ->assertJsonPath('pages', 2)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedNoResults()
    {
        $this
            ->json('GET', '/api/products')
            ->assertJsonCount(0, 'products')
            ->assertJsonPath('total', 0)
            ->assertJsonPath('pages', 0)
            ->assertStatus(200);
    }
}
