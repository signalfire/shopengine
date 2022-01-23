<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\GetProductsRequest;
use Signalfire\Shopengine\Http\Resources\ProductCollection;
use Signalfire\Shopengine\Models\Product;

class ProductsController extends Controller
{
    /**
     * Gets paginated products.
     *
     * @param Signalfire\Shopengine\Http\Requests\GetProductsRequest $request
     *
     * @return string JSON
     */
    public function index(GetProductsRequest $request)
    {
        $size = (int) $request->query('size', 10);
        $page = (int) $request->query('page', 1);
        $skip = $page === 1 ? 0 : ($page - 1) * $size;
        $products = Product::available()
            ->skip($skip)
            ->take($size)
            ->get();
        $total = Product::available()
            ->count();

        return (new ProductCollection($products))
            ->additional([
                'meta' => [
                    'total' => $total,
                    'pages' => ceil($total / $size),
                ],
            ]);
    }
}
