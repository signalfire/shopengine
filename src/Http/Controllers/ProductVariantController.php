<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Requests\StoreProductVariantRequest;
use Signalfire\Shopengine\Http\Requests\UpdateProductVariantRequest;
use Signalfire\Shopengine\Http\Resources\ProductVariantResource;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class ProductVariantController extends Controller
{
    /**
     * Gets product variant.
     *
     * @param Signalfire\Shopengine\Models\Product        $product
     * @param Signalfire\Shopengine\Models\ProductVariant $variant
     *
     * @return string JSON
     */
    public function show(Product $product, ProductVariant $variant)
    {
        return (new ProductVariantResource($variant))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Creates a new product variant.
     *
     * @param StoreProductVariantRequest           $request
     * @param Signalfire\Shopengine\Models\Product $product
     *
     * @return string JSON
     */
    public function store(StoreProductVariantRequest $request, Product $product)
    {
        $validated = $request->validated();

        $variant = ProductVariant::create($validated);

        return (new ProductVariantResource($variant))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Updates existing product variant.
     *
     * @param UpdateProductVariantRequest                 $request
     * @param Signalfire\Shopengine\Models\Product        $product
     * @param Signalfire\Shopengine\Models\ProductVariant $variant
     *
     * @return string JSON
     */
    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validated();
        $variant->update($validated);
        // $product_id = $validated['product_id'];
        // $name = $validated['name'];
        // $slug = $validated['slug'];
        // $stock = $validated['stock'];
        // $price = $validated['price'];
        // $length = $validated['length'];
        // $width = $validated['width'];
        // $height = $validated['height'];
        // $status = $validated['status'];

        // $variant->product_id = $product_id;
        // $variant->name = $name;
        // $variant->slug = $slug;
        // $variant->stock = $stock;
        // $variant->price = $price;
        // $variant->length = $length;
        // $variant->width = $width;
        // $variant->height = $height;
        // $variant->status = $status;
        // $variant->save();

        $variant->refresh();

        return (new ProductVariantResource($variant))
            ->response()
            ->setStatusCode(204);
    }
}
