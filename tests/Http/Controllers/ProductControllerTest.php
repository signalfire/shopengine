<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class ProductControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $this->user->roles()->attach($this->role);
    }

    public function testGetProductById()
    {
        $product = Product::factory()->create();

        $this
            ->json('GET', '/api/product/'.$product->id)
            ->assertStatus(200);
    }

    public function testGetProductByIdWithVariantsCategories()
    {
        $product = Product::factory()->create();
        $variants = ProductVariant::factory()->state([
            'product_id' => $product->id,
        ])->count(2)->create();
        $categories = Category::factory()->count(4)->create();
        foreach ($categories as $category) {
            $product->categories()->attach($category);
        }
        $this
            ->json('GET', '/api/product/'.$product->id)
            ->assertJsonCount(2, 'data.variants')
            ->assertJsonCount(4, 'data.categories')
            ->assertStatus(200);
    }

    public function testGetProductBySlug()
    {
        $product = Product::factory()->create();

        $this
            ->json('GET', '/api/product/'.$product->slug)
            ->assertStatus(200);
    }

    public function testFailsGetProductByIdMissing()
    {
        $this
            ->json('GET', '/api/product/'.(string) Str::uuid())
            ->assertStatus(404);
    }

    public function testFailsGetProductBySlugMissing()
    {
        $this
            ->json('GET', '/api/product/test')
            ->assertStatus(404);
    }

    public function testFailsCreateProductNameMissing()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', '/api/product', [
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductSlugMissing()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductStatusMissing()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', '/api/product', [
                'name' => 'test',
                'slug' => 'slug',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductNameSlugTooLong()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', '/api/product', [
                'name'   => str_repeat('x', 101),
                'slug'   => str_repeat('x', 101),
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductStatusNotInteger()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 'A',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductProductSlugExists()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->state([
            'slug' => 'test',
        ])->create();

        $this
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testCreateProduct()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertStatus(201);
    }

    public function testFailsCreateProductNotInPolicyRole()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertStatus(403);
    }

    public function testUpdatesProduct()
    {
        Sanctum::actingAs($this->user);

        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;

        $product = Product::factory()->create();

        $response = $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => $name,
                'slug'   => $slug,
                'status' => $status,
            ])
            ->assertStatus(204);

        $this->assertDatabaseCount('products', 1);

        $this->assertDatabaseHas('products', [
            'id'     => $product->id,
            'name'   => $name,
            'slug'   => $slug,
            'status' => $status,
        ]);
    }

    public function testFailsUpdatingProductNotInPolicyRole()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $product = Product::factory()->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => 'this is a test',
                'slug'   => 'this-is-a-test',
                'status' => 1,
            ])
            ->assertStatus(403);
    }

    public function testFailsUpdatingProductNameMissing()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductSlugMissing()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductStatusMissing()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name' => 'test',
                'slug' => 'slug',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductNameSlugTooLong()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => str_repeat('x', 101),
                'slug'   => str_repeat('x', 101),
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductStatusNotInteger()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 'A',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductProductSlugExists()
    {
        Sanctum::actingAs($this->user);

        $product = Product::factory()->state([
            'slug' => 'test',
        ])->create();

        $this
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }
}
