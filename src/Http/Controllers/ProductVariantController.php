<?php

namespace Signalfire\Shopengine\Http\Controllers;

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
}
