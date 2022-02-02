<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Illuminate\Http\Request;

use Signalfire\Shopengine\Models\Product;

class ProductImageController extends Controller {
    public function store(Request $request, Product $product) {
        $product->addMediaFromRequest('image')->toMediaCollection('images');
    }   
}