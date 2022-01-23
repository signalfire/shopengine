<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Product;

class ProductsControllerTest extends TestCase
{
    public function testGetProducts()
    {
        $products = Product::factory()->create();
        $this
            ->json('GET', '/api/products')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedNoParams()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', '/api/products')
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('data.0.id', $products[0]->id)
            ->assertJsonPath('data.9.id', $products[9]->id)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 2)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedPageParams()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', '/api/products?page=2')
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('data.0.id', $products[10]->id)
            ->assertJsonPath('data.9.id', $products[19]->id)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 2)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedChangePageSize()
    {
        $products = Product::factory()->count(10)->create();
        $this
            ->json('GET', '/api/products?size=5')
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.id', $products[0]->id)
            ->assertJsonPath('data.4.id', $products[4]->id)
            ->assertJsonPath('meta.total', 10)
            ->assertJsonPath('meta.pages', 2)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedNoResults()
    {
        $this
            ->json('GET', '/api/products')
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0)
            ->assertJsonPath('meta.pages', 0)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedInvalidPageQueryNotNumber()
    {
        $this
            ->json('GET', '/api/products?page=A')
            ->assertJsonValidationErrorFor('page', 'errors')
            ->assertStatus(422);
    }

    public function testGetProductsPaginatedInvalidSizeQueryNotNumber()
    {
        $this
            ->json('GET', '/api/products?size=A')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testGetProductsPaginatedSizeQueryNumberAtMax()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', '/api/products?size=50')
            ->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedInvalidSizeQueryNumberGreaterThanMax()
    {
        $products = Product::factory()->count(20)->create();

        $this
            ->json('GET', '/api/products?size=51')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }
}
