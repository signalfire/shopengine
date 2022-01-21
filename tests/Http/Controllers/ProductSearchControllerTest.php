<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Product;

class ProductSearchControllerTest extends TestCase
{
    public function testGetProductSearchPaginated()
    {
        $product = Product::factory()->create();
        $this
            ->json('GET', '/api/products/search?keywords='.$product->name)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testFailsGetProductSearchPaginatedKeywordsMissing()
    {
        $product = Product::factory()->create();
        $this
            ->json('GET', '/api/products/search')
            ->assertJsonValidationErrorFor('keywords', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedKeywordsTooLong()
    {
        $this
            ->json('GET', '/api/products/search?keywords='.str_repeat('x', 101))
            ->assertJsonValidationErrorFor('keywords', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedInvalidPageQueryNotNumber()
    {
        $this
            ->json('GET', '/api/products/search?keywords=A&page=A')
            ->assertJsonValidationErrorFor('page', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedInvalidSizeQueryNotNumber()
    {
        $this
            ->json('GET', '/api/products/search?keywords=A&size=A')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedInvalidSizeQueryNumberGreaterThanMax()
    {
        $this
            ->json('GET', '/api/products/search?keywords=A&size=51')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testFailsGetProductSearchPaginatedSizeQueryNumberAtMax()
    {
        $products = Product::factory()->count(20)->state([
            'name' => 'A',
        ])->create();
        $this
            ->json('GET', '/api/products/search?keywords=A&size=50')
            ->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }
}
