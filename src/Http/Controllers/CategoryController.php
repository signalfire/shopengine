<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\StoreCategoryRequest;
use Signalfire\Shopengine\Http\Requests\UpdateCategoryRequest;
use Signalfire\Shopengine\Models\Category;

class CategoryController extends Controller
{
    /**
     * Gets all categories.
     *
     * @return string JSON
     */
    public function index()
    {
        $categories = Category::available()->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Gets category by id.
     *
     * @param string $category_id
     *
     * @return string JSON
     */
    public function showById($category_id)
    {
        $category = Category::available()->where('id', $category_id)->first();

        if (!$category) {
            return response()->json(['error' => __('Category not found')]);
        }

        return response()->json(['category' => $category]);
    }

    /**
     * Gets category by slug.
     *
     * @param string $slug
     *
     * @return string JSON
     */
    public function showBySlug($slug)
    {
        $category = Category::available()->where('slug', $slug)->first();

        if (!$category) {
            return response()->json(['error' => __('Category not found')]);
        }

        return response()->json(['category' => $category]);
    }

    /**
     * Creates a new category.
     *
     * @param StoreCategoryRequest $request
     *
     * @return string JSON
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::create($validated);

        return response()->json(['category' => $category], 201);
    }

    /**
     * Updates existing category.
     *
     * @param UpdateCategoryRequest $request
     *
     * @return string JSON
     */
    public function update(UpdateCategoryRequest $request, $category_id)
    {
        $validated = $request->validated();
        $name = $validated['name'];
        $slug = $validated['slug'];
        $status = $validated['status'];

        $category = Category::where('id', $category_id)->first();

        if (!$category) {
            return response()->json(['error' => __('Category not found')]);
        }

        $category->name = $name;
        $category->slug = $slug;
        $category->status = $status;
        $category->save();

        $category->refresh();

        return response()->json(['category' => $category], 204);
    }
}
