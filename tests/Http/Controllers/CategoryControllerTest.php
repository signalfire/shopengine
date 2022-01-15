<?php

namespace Signalfire\Shopengine\Tests;

use Signalfire\Shopengine\Models\Category;

class CategoryControllerTest extends TestCase
{
    /** @test */
    public function itGetsCategories()
    {
        $categories = Category::factory()->count(3)->create();
        $this->json('GET', '/api/categories')
            ->assertJsonCount(3, 'categories')
            ->assertStatus(200);
    }

    /** @test */
    public function itFailsToAddCategory()
    {
        $this
            ->json('POST', '/api/category', [])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsToAddCategoryNameMissing()
    {
        $this
            ->json('POST', '/api/category', ['slug' => 'test', 'status' => 1])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsToAddCategorySlugMissing()
    {
        $this
            ->json('POST', '/api/category', ['name' => 'test', 'status' => 1])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsToAddCategoryStatusMissing()
    {
        $this
            ->json('POST', '/api/category', ['name' => 'test', 'slug' => 'test'])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsToAddCategoryNameSlugTooLong()
    {
        $this
            ->json('POST', '/api/category', [
                'name'   => str_repeat('a', 101),
                'slug'   => str_repeat('a', 101),
                'status' => 1,
            ])
            ->assertJsonValidationErrorFor('name', 'errors')
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsToAddCategoryStatusNotInteger()
    {
        $this
            ->json('POST', '/api/category', [
                'name'   => 'test',
                'slug'   => 'test',
                'status' => 'a',
            ])
            ->assertJsonValidationErrorFor('status', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itFailsAddCategorySlugExists()
    {
        $category = Category::factory()->create();
        $this
            ->json('POST', '/api/category', [
                'name'   => $category->name,
                'slug'   => $category->slug,
                'status' => $category->status,
            ])
            ->assertJsonValidationErrorFor('slug', 'errors')
            ->assertStatus(422);
    }

    /** @test */
    public function itAddsCategory()
    {
        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;

        $this
            ->json('POST', '/api/category', [
                'name'   => $name,
                'slug'   => $slug,
                'status' => $status,
            ])
            ->assertJson([
                'category' => [
                    'name'   => $name,
                    'slug'   => $slug,
                    'status' => $status,
                ],
            ])
            ->assertStatus(201);

        $this->assertDatabaseCount('categories', 1);

        $this->assertDatabaseHas('categories', [
            'name'   => $name,
            'slug'   => $slug,
            'status' => $status,
        ]);
    }

    /** @test */
    public function itUpdatesCategory()
    {
        $name = 'this is a test';
        $slug = 'this-is-a-test';
        $status = 1;
        $category = Category::factory()->create();
        $this
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
}
