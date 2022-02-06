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
     * Gets category.
     *
     * @param Signalfire\Shopengine\Models\Category $category
     *
     * @return string JSON
     */
    public function show(Category $category)
    {
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
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $category->update($validated);

        $category->refresh();

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(204);
    }
}
