<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class ProductControllerTest extends TestCase
{
    public function testGetProductById()
    {
        $product = Product::factory()->create();

        $this
            ->json('GET', '/api/product/'.$product->id)
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
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $this
            ->actingAs($user)
            ->json('POST', '/api/product', [
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductSlugMissing()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $this
            ->actingAs($user)
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductStatusMissing()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $this
            ->actingAs($user)
            ->json('POST', '/api/product', [
                'name' => 'test',
                'slug' => 'slug',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsCreateProductNameSlugTooLong()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $this
            ->actingAs($user)
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
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $this
            ->actingAs($user)
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
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $product = Product::factory()->state([
            'slug' => 'test',
        ])->create();

        $this
            ->actingAs($user)
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
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);
        $this
            ->actingAs($user)
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
        $this
            ->actingAs($user)
            ->json('POST', '/api/product', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertStatus(403);
    }

    public function testUpdatesProduct()
    {
        $user = User::factory()->create();
        
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        
        $user->roles()->attach($role);

        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;

        $product = Product::factory()->create();
        
        $response = $this
            ->actingAs($user)
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
        $product = Product::factory()->create();
        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/'.$product->id, [
                'name'   => 'this is a test',
                'slug'   => 'this-is-a-test',
                'status' => 1,
            ])
            ->assertStatus(403);
    }

    public function testFailsUpdatingProductNameMissing()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);
        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/' . $product->id, [
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductSlugMissing()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);
        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/' . $product->id, [
                'name'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductStatusMissing()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);
        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/' . $product->id, [
                'name' => 'test',
                'slug' => 'slug',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductNameSlugTooLong()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);
        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/' . $product->id, [
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
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);
        $product = Product::factory()->create();

        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/'. $product->id, [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 'A',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsUpdatingProductProductSlugExists()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin',
        ])->create();
        $user->roles()->attach($role);

        $product = Product::factory()->state([
            'slug' => 'test',
        ])->create();

        $this
            ->actingAs($user)
            ->json('PUT', '/api/product/' . $product->id, [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }
}
