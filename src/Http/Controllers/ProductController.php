<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\StoreProductRequest;
use Signalfire\Shopengine\Http\Resources\ProductResource;
use Signalfire\Shopengine\Models\Product;

class ProductController extends Controller
{
    /**
     * Gets product.
     *
     * @param Signalfire\Shopengine\Models\Product $product
     *
     * @return string JSON
     */
    public function show(Product $product)
    {
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Creates a new product.
     *
     * @param StoreProductRequest $request
     *
     * @return string JSON
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create($validated);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }
}
