<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Signalfire\Shopengine\Http\Requests\StoreProductVariantImageRequest;
use Signalfire\Shopengine\Http\Resources\ProductVariantResource;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

class ProductVariantImageController extends Controller
{
    /**
     * Uploads a new product variant image.
     *
     * @param StoreProductVariantImageRequest             $request
     * @param Signalfire\Shopengine\Models\Product        $product
     * @param Signalfire\Shopengine\Models\ProductVariant $variant
     *
     * @return string JSON
     */
    public function store(StoreProductVariantImageRequest $request, Product $product, ProductVariant $variant)
    {
        $variant->addMediaFromRequest('image')->toMediaCollection('images');

        $variant->refresh();

        return (new ProductVariantResource($variant))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Deletes a product variant image.
     *
     * @param Signalfire\Shopengine\Models\Product              $product
     * @param Signalfire\Shopengine\Models\ProductVariant       $variant
     * @param Spatie\MediaLibrary\MediaCollections\Models\Media $media
     *
     * @return string JSON
     */
    public function destroy(Product $product, ProductVariant $variant, Media $image)
    {
        $image->delete();

        $variant->refresh();

        return (new ProductVariantResource($variant))
            ->response()
            ->setStatusCode(202);
    }
}
