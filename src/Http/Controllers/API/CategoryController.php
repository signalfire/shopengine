<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Signalfire\Shopengine\Http\Requests\StoreCategoryRequest;
use Signalfire\Shopengine\Http\Requests\UpdateCategoryRequest;
use Signalfire\Shopengine\Http\Resources\CategoryResource;
use Signalfire\Shopengine\Interfaces\CategoryRepositoryInterface;
use Signalfire\Shopengine\Models\Category;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Gets all categories.
     *
     * @return string JSON
     */
    public function index()
    {
        $categories = $this->categoryRepository->getCategories();

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
        $category = $this->categoryRepository->createCategory($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Updates existing category.
     *
     * @param UpdateCategoryRequest $request
     * @param Category              $category
     *
     * @return string JSON
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->categoryRepository->updateCategory($category, $request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(204);
    }
}
