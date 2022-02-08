<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Product;

class CategoryProductControllerTest extends TestCase
{
    public function testGetCategoryProducts()
    {
        $products = Product::factory()->count(5)->create();
        $category = Category::factory()->create();

        foreach ($products as $product) {
            $product->categories()->attach($category->id);
        }

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]))
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.total', 5)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testGetCategoryProductsPaginatedNoParams()
    {
        $products = Product::factory()->count(20)->create();
        $category = Category::factory()->create();

        foreach ($products as $product) {
            $product->categories()->attach($category->id);
        }

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]))
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('data.0.id', $products[0]->id)
            ->assertJsonPath('data.9.id', $products[9]->id)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 2)
            ->assertStatus(200);
    }

    public function testGetCategoryProductsPaginatedPageParams()
    {
        $products = Product::factory()->count(20)->create();
        $category = Category::factory()->create();

        foreach ($products as $product) {
            $product->categories()->attach($category->id);
        }

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]).'?page=2')
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('data.0.id', $products[10]->id)
            ->assertJsonPath('data.9.id', $products[19]->id)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 2)
            ->assertStatus(200);
    }

    public function testGetCategoryProductsPaginatedChangePageSize()
    {
        $products = Product::factory()->count(20)->create();
        $category = Category::factory()->create();

        foreach ($products as $product) {
            $product->categories()->attach($category->id);
        }

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]).'?size=10')
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('data.0.id', $products[0]->id)
            ->assertJsonPath('data.4.id', $products[4]->id)
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 2)
            ->assertStatus(200);
    }

    public function testGetCategoryProductsPaginatedNoResults()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]))
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0)
            ->assertJsonPath('meta.pages', 0)
            ->assertStatus(200);
    }

    public function testGetCategoryProductsPaginatedInvalidPageQueryNotNumber()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]).'?page=A')
            ->assertJsonValidationErrorFor('page', 'errors')
            ->assertStatus(422);
    }

    public function testGetCategoryProductsPaginatedInvalidSizeQueryNotNumber()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]).'?size=A')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }

    public function testGetCategoryProductsPaginatedSizeQueryNumberAtMax()
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(20)->create();
        foreach ($products as $product) {
            $product->categories()->attach($category->id);
        }
        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]).'?size=50')
            ->assertJsonCount(20, 'data')
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.pages', 1)
            ->assertStatus(200);
    }

    public function testGetCategoryProductsPaginatedInvalidSizeQueryNumberGreaterThanMax()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', route('category.products.index', ['category' => $category->id]).'?size=51')
            ->assertJsonValidationErrorFor('size', 'errors')
            ->assertStatus(422);
    }
}
