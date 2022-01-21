<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\GetProductSearchRequest;
use Signalfire\Shopengine\Http\Resources\ProductCollection;
use Signalfire\Shopengine\Models\Product;

class ProductSearchController extends Controller
{
    /**
     * Gets paginated products.
     *
     * @param Signalfire\Shopengine\Http\Requests\GetProductSearchRequest $request
     *
     * @return string JSON
     */
    public function index(GetProductSearchRequest $request)
    {
        $keywords = $request->query('keywords');
        $size = (int) $request->query('size', 10);
        $page = (int) $request->query('page', 1);
        $skip = $page === 1 ? 0 : ($page - 1) * $size;
        try {
            $products = Product::available()
                ->search(['name'], $keywords)
                ->skip($skip)
                ->take($size)
                ->get();
            $total = Product::available()
                ->search(['name'], $keywords)
                ->count();
        } catch (\Throwable $ex) {
            var_dump($ex);
        }

        return (new ProductCollection($products))
            ->additional([
                'meta' => [
                    'total' => $total,
                    'pages' => ceil($total / $size),
                ],
            ]);
    }
}
