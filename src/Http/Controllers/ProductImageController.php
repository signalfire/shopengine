<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Illuminate\Http\Request;

use Signalfire\Shopengine\Http\Requests\StoreProductImageRequest;
use Signalfire\Shopengine\Http\Resources\ProductResource;
use Signalfire\Shopengine\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductImageController extends Controller {

    /**
     * Uploads a new product image.
     *
     * @param StoreProductImageRequest $request
     *
     * @return string JSON
     */
    public function store(StoreProductImageRequest $request, Product $product) {

        $product->addMediaFromRequest('image')->toMediaCollection('images');

        $product->refresh();

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }   

    /**
     * Deletes a product image.
     *
     * @param Signalfire\Shopengine\Models\Product $product
     * @param Spatie\MediaLibrary\MediaCollections\Models\Media $media
     *
     * @return string JSON
     */
    public function destroy(Product $product, Media $image)
    {
        $image->delete();

        $product->refresh();

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(202);
    }
}
