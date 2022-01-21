<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\GetProductInCategoryRequest;
use Signalfire\Shopengine\Models\Category;

class CategoryProductController extends Controller
{
    /**
     * Gets paginated products in a category.
     *
     * @param Illuminate\Http\Request $request,
     * @param string                  $category_id
     *
     * @return string JSON
     */
    public function index(GetProductInCategoryRequest $request, $category_id)
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

        return response()->json([
            'products' => $products,
            'total'    => $total,
            'pages'    => ceil($total / $size),
        ]);
    }
}
