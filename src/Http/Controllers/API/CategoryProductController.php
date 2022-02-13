<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Signalfire\Shopengine\Http\Requests\GetCategoryProductsRequest;
use Signalfire\Shopengine\Http\Resources\ProductCollection;
use Signalfire\Shopengine\Interfaces\CategoryProductRepositoryInterface;
use Signalfire\Shopengine\Models\Category;

class CategoryProductController extends Controller
{
    private CategoryProductRepositoryInterface $categoryProductRepository;

    public function __construct(CategoryProductRepositoryInterface $categoryProductRepository)
    {
        $this->categoryProductRepository = $categoryProductRepository;
    }

    /**
     * Gets paginated products in a category.
     *
     * @param Signalfire\Shopengine\Http\Requests\GetCategoryProductsRequest $request,
     * @param string                                                         $category_id
     *
     * @return string JSON
     */
    public function index(GetCategoryProductsRequest $request, Category $category)
    {
        $size = (int) $request->query('size', 10);
        $page = (int) $request->query('page', 1);
        $skip = $page === 1 ? 0 : ($page - 1) * $size;

        $products = $this->categoryProductRepository->getCategoryProducts($category, $size, $page, $skip);
        $total = $this->categoryProductRepository->getCategoryProductsTotal($category);

        return (new ProductCollection($products))
            ->additional([
                'meta' => [
                    'total' => $total,
                    'pages' => ceil($total / $size),
                ],
            ]);
    }
}
