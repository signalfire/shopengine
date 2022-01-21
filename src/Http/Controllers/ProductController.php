<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Illuminate\Http\Request;
use Signalfire\Shopengine\Models\Product;

class ProductController extends Controller
{
    /**
     * Gets paginated products.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return string JSON
     */
    public function index(Request $request)
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

        return response()->json([
            'products' => $products,
            'total'    => $total,
            'pages'    => ceil($total / $size),
        ]);
    }
}
