<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\GetCategoryProductsRequest;
use Signalfire\Shopengine\Http\Resources\ProductCollection;
use Signalfire\Shopengine\Models\Category;

class CategoryProductController extends Controller
{
    /**
     * Gets paginated products in a category.
     *
     * @param Signalfire\Shopengine\Http\Requests\GetCategoryProductsRequest $request,
     * @param string                                                         $category_id
     *
     * @return string JSON
     */
    public function index(GetCategoryProductsRequest $request, $category_id)
    {
        $size = (int) $request->query('size', 10);
        $page = (int) $request->query('page', 1);
        $skip = $page === 1 ? 0 : ($page - 1) * $size;
        $category = Category::where('id', $category_id)->available()->first();
        $products = $category
            ->products()
            ->available()
            ->skip($skip)
            ->take($size)
            ->get();
        $total = $category
            ->products()
            ->available()
            ->count();

        return (new ProductCollection($products))
            ->additional([
                'meta' => [
                    'total' => $total,
                    'pages' => ceil($total / $size)
                ]
            ]);
    }
}
