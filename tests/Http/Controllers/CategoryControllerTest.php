<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class CategoryControllerTest extends TestCase
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

    public function testGetsCategories()
    {
        $categories = Category::factory()->count(3)->create();
        $this->json('GET', route('categories.index'))
            ->assertJsonCount(3, 'data')
            ->assertStatus(200);
    }

    public function testFailsToAddCategory()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', route('category.store'), [])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryNameMissing()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', route('category.store'), ['slug' => 'test', 'status' => 1])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategorySlugMissing()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', route('category.store'), ['name' => 'test', 'status' => 1])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryStatusMissing()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', route('category.store'), ['name' => 'test', 'slug' => 'test'])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryNameSlugTooLong()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', route('category.store'), [
                'name'   => str_repeat('a', 101),
                'slug'   => str_repeat('a', 101),
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryStatusNotInteger()
    {
        Sanctum::actingAs($this->user);

        $this
            ->json('POST', route('category.store'), [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 'a',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddCategorySlugExists()
    {
        Sanctum::actingAs($this->user);

        $category = Category::factory()->create();

        $this
            ->json('POST', route('category.store'), [
                'name'   => $category->name,
                'slug'   => $category->slug,
                'status' => $category->status,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testAddsCategory()
    {
        Sanctum::actingAs($this->user);

        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;

        $this
            ->json('POST', route('category.store'), [
                'name'   => $name,
                'slug'   => $slug,
                'status' => $status,
            ])
            ->assertStatus(201);

        $this->assertDatabaseCount('categories', 1);

        $this->assertDatabaseHas('categories', [
            'name'   => $name,
            'slug'   => $slug,
            'status' => $status,
        ]);
    }

    public function testFailsAddingCategoryNotInPolicyRole()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this
            ->json('POST', route('category.store'), [
                'name'   => 'this is a test',
                'slug'   => 'this-is-a-test',
                'status' => 1,
            ])
            ->assertStatus(403);
    }

    public function testUpdatesCategory()
    {
        Sanctum::actingAs($this->user);

        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;
        $category = Category::factory()->create();

        $this
            ->json('PUT', route('category.update', ['category' => $category->id]), [
                'name'   => $name,
                'slug'   => $slug,
                'status' => $status,
            ])
            ->assertStatus(204);

        $this->assertDatabaseCount('categories', 1);

        $this->assertDatabaseHas('categories', [
            'id'     => $category->id,
            'name'   => $name,
            'slug'   => $slug,
            'status' => $status,
        ]);
    }

    public function testFailsUpdatingCategoryNotInPolicyRole()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $category = Category::factory()->create();

        $this
            ->json('PUT', route('category.update', ['category' => $category->id]), [
                'name'   => 'this is a test',
                'slug'   => 'this-is-a-test',
                'status' => 1,
            ])
            ->assertStatus(403);
    }

    public function testGetCategoryById()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', route('category.show', ['category' => $category->id]))
            ->assertStatus(200);
    }

    public function testGetCategoryBySlug()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', route('category.show', ['category' => $category->slug]))
            ->assertStatus(200);
    }

    public function testFailsGetByIdMissing()
    {
        $this
            ->json('GET', route('category.show', (string) Str::uuid()))
            ->assertStatus(404);
    }

    public function testFailsGetBySlugNotFound()
    {
        $this
            ->json('GET', route('category.show', 'test'))
            ->assertStatus(404);
    }
}
