<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Signalfire\Shopengine\Http\Requests\StoreProductRequest;
use Signalfire\Shopengine\Http\Requests\UpdateProductRequest;
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

    /**
     * Updates existing product.
     *
     * @param UpdateProductRequest $request
     *
     * @return string JSON
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update($validated);

        $product->refresh();

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(204);
    }
}
