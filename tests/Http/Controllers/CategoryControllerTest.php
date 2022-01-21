<?php

namespace Signalfire\Shopengine\Tests;

use Illuminate\Support\Str;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\User;
use Signalfire\Shopengine\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Spatie\Permission\PermissionRegistrar;

class CategoryControllerTest extends TestCase
{

    public function testGetsCategories()
    {
        $categories = Category::factory()->count(3)->create();
        $this->json('GET', '/api/categories')
            ->assertJsonCount(3, 'data')
            ->assertStatus(200);
    }

    public function testFailsToAddCategory()
    {
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', [])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryNameMissing()
    {
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', ['slug' => 'test', 'status' => 1])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategorySlugMissing()
    {
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', ['name' => 'test', 'status' => 1])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryStatusMissing()
    {
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', ['name' => 'test', 'slug' => 'test'])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsToAddCategoryNameSlugTooLong()
    {
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', [
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
        $user = User::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 'a',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    public function testFailsAddCategorySlugExists()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $this
            ->actingAs($user)
            ->json('POST', '/api/category', [
                'name'   => $category->name,
                'slug'   => $category->slug,
                'status' => $category->status,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    public function testAddsCategory()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin'
        ])->create();
        $user->roles()->attach($role);
        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;

        $this
            ->actingAs($user)
            ->json('POST', '/api/category', [
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

        $this->expectException(HttpException::class);

        $this
            ->actingAs($user)
            ->withoutExceptionHandling()
            ->json('POST', '/api/category', [
                'name'   => 'this is a test',
                'slug'   => 'this-is-a-test',
                'status' => 1,
            ]);
    }


    public function testUpdatesCategory()
    {
        $user = User::factory()->create();
        $role = Role::factory()->state([
            'name' => 'admin'
        ])->create();
        $user->roles()->attach($role);
        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;
        $category = Category::factory()->create();
        $this
            ->actingAs($user)
            ->json('PUT', '/api/category/'.$category->id, [
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

        $category = Category::factory()->create();

        $this->expectException(HttpException::class);

        $this
            ->actingAs($user)
            ->withoutExceptionHandling()
            ->json('PUT', '/api/category/'.$category->id, [
                'name'   => 'this is a test',
                'slug'   => 'this-is-a-test',
                'status' => 1,
            ]);
    }


    // Needs extended
    public function testGetCategoryById()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', '/api/category/'.$category->id)
            ->assertStatus(200);
    }

    public function testFailsGetByIdMissing()
    {
        $this->expectException(HttpException::class);

        $this
            ->withoutExceptionHandling()
            ->json('GET', '/api/category/'.(string) Str::uuid());
    }

    // Needs extended
    public function testGetCategoryBySlug()
    {
        $category = Category::factory()->create();

        $this
            ->json('GET', '/api/category/'.$category->slug)
            ->assertStatus(200);
    }
}
