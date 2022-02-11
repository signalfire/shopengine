<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Product;

class ProductsControllerTest extends TestCase
{
    public function testGetProducts()
    {
        $products = Product::factory()->create();
        $this
            ->json('GET', route('products.index'))
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedNoParams()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', route('products.index'))
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
            ->json('GET', route('products.index').'?page=2')
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
            ->json('GET', route('products.index').'?size=5')
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
            ->json('GET', route('products.index'))
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0)
            ->assertJsonPath('meta.pages', 0)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedInvalidPageQueryNotNumber()
    {
        $this
            ->json('GET', route('products.index').'?page=A')
            ->assertJsonValidationErrorFor('page', 'errors')
            ->assertStatus(422);
    }

    public function testGetProductsPaginatedInvalidSizeQueryNotNumber()
    {
        $this
            ->json('GET', route('products.index').'?size=A')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testGetProductsPaginatedSizeQueryNumberAtMax()
    {
        $products = Product::factory()->count(20)->create();
        $this
            ->json('GET', route('products.index').'?size=50')
            ->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testGetProductsPaginatedInvalidSizeQueryNumberGreaterThanMax()
    {
        $products = Product::factory()->count(20)->create();

        $this
            ->json('GET', route('products.index').'?size=51')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testGetProductSearchPaginated()
    {
        $product = Product::factory()->create();
        $this
            ->json('GET', route('products.index').'?keywords='.$product->name)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testFailsGetProductSearchPaginatedKeywordsMissing()
    {
        $product = Product::factory()->create();
        $this
            ->json('GET', route('products.search.index'))
            ->assertJsonValidationErrorFor('keywords', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedKeywordsTooLong()
    {
        $this
            ->json('GET', route('products.search.index').'?keywords='.str_repeat('x', 101))
            ->assertJsonValidationErrorFor('keywords', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedInvalidPageQueryNotNumber()
    {
        $this
            ->json('GET', route('products.search.index').'?keywords=A&page=A')
            ->assertJsonValidationErrorFor('page', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedInvalidSizeQueryNotNumber()
    {
        $this
            ->json('GET', route('products.search.index').'?keywords=A&size=A')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedInvalidSizeQueryNumberGreaterThanMax()
    {
        $this
            ->json('GET', route('products.search.index').'?keywords=A&size=51')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedSizeQueryNumberAtMax()
    {
        $products = Product::factory()->count(20)->state([
            'name' => 'A',
        ])->create();
        $this
            ->json('GET', route('products.search.index').'?keywords=A&size=50')
            ->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }
}
