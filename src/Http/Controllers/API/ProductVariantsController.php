<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Signalfire\Shopengine\Http\Resources\ProductVariantResource;
use Signalfire\Shopengine\Models\Product;

class ProductVariantsController extends Controller
{
    /**
     * Gets all product variants.
     *
     * @return string JSON
     */
    public function index(Product $product)
    {
        $variants = $product->variants()->available()->get();

        return (ProductVariantResource::collection($variants))
            ->response()
            ->setStatusCode(200);
    }
}
