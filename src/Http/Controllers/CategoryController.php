<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\StoreCategoryRequest;
use Signalfire\Shopengine\Http\Requests\UpdateCategoryRequest;
use Signalfire\Shopengine\Http\Resources\CategoryResource;
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

        return (CategoryResource::collection($categories))
            ->response()
            ->setStatusCode(200);
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
            abort(404, __('Unable to find category'));
        }

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(200);
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
            abort(404, __('Unable to find category'));
        }

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(200);
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
        if ($request->user()->cannot('create', Category::class)) {
            abort(403, __('Unable to create category'));
        }

        $validated = $request->validated();

        $category = Category::create($validated);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
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
        $category = Category::where('id', $category_id)->first();

        if (!$category) {
            abort(404, __('Unable to find category'));
        }

        if ($request->user()->cannot('update', $category)) {
            abort(403, __('Unable to update category'));
        }

        $validated = $request->validated();
        $name = $validated['name'];
        $slug = $validated['slug'];
        $status = $validated['status'];

        $category->name = $name;
        $category->slug = $slug;
        $category->status = $status;
        $category->save();

        $category->refresh();

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(204);
    }
}
