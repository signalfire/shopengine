<?php

use Signalfire\Shopengine\Http\Controllers\API\BasketController;
use Signalfire\Shopengine\Http\Controllers\API\BasketItemController;
use Signalfire\Shopengine\Http\Controllers\API\CategoryController;
use Signalfire\Shopengine\Http\Controllers\API\CategoryProductController;
use Signalfire\Shopengine\Http\Controllers\API\OrderController;
use Signalfire\Shopengine\Http\Controllers\API\OrderInvoiceController;
use Signalfire\Shopengine\Http\Controllers\API\ProductController;
use Signalfire\Shopengine\Http\Controllers\API\ProductImageController;
use Signalfire\Shopengine\Http\Controllers\API\ProductsController;
use Signalfire\Shopengine\Http\Controllers\API\ProductVariantController;
use Signalfire\Shopengine\Http\Controllers\API\ProductVariantImageController;
use Signalfire\Shopengine\Http\Controllers\API\ProductVariantsController;
use Signalfire\Shopengine\Http\Controllers\API\TokenController;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\ProductVariant;

Route::middleware(['api'])
    ->prefix('api')
    ->group(function () {
        Route::prefix('basket')->group(function () {
            Route::post('/', [BasketController::class, 'store'])
                ->name('basket.store');
            Route::get('/{basket}', [BasketController::class, 'show'])
                ->name('basket.show');
            Route::delete('/{basket}', [BasketController::class, 'destroy'])
                ->name('basket.destroy');
            Route::post('/{basket}/items', [BasketItemController::class, 'store'])
                ->name('basket.item.store');
            Route::delete('/{basket}/items', [BasketItemController::class, 'destroy'])
                ->name('basket.item.destroy');
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])
                ->name('categories.index');
        });
        Route::prefix('category')->group(function () {
            Route::get('/{category}', [CategoryController::class, 'show'])
                ->name('category.show');
            Route::get('/{category}/products', [CategoryProductController::class, 'index'])
                ->name('category.products.index');
            Route::middleware(['auth:sanctum'])->group(function () {
                Route::post('/', [CategoryController::class, 'store'])
                    ->name('category.store')
                    ->can('create', Category::class);
                Route::put('/{category}', [CategoryController::class, 'update'])
                    ->name('category.update')
                    ->can('update', 'category');
            });
        });
        Route::prefix('product')->group(function () {
            Route::get('/{product}', [ProductController::class, 'show'])
                ->name('product.show');
            Route::get('/{product}/variants', [ProductVariantsController::class, 'index'])
                ->name('product.variants.index');
            Route::get('/{product}/variant/{variant}', [ProductVariantController::class, 'show'])
                ->name('product.variant.show');
            Route::middleware(['auth:sanctum'])->group(function () {
                Route::post('/', [ProductController::class, 'store'])
                    ->name('product.store')
                    ->can('create', Product::class);
                Route::put('/{product}', [ProductController::class, 'update'])
                    ->name('product.update')
                    ->can('update', 'product');
                Route::post('/{product}/image', [ProductImageController::class, 'store'])
                    ->name('product.image.store')
                    ->can('create', Product::class);
                Route::delete('/{product}/image/{image}', [ProductImageController::class, 'destroy'])
                    ->name('product.image.destroy')
                    ->can('update', 'product');
                Route::post('/{product}/variant', [ProductVariantController::class, 'store'])
                    ->name('product.variant.store')
                    ->can('create', ProductVariant::class);
                Route::put('/{product}/variant/{variant}', [ProductVariantController::class, 'update'])
                    ->name('product.variant.update')
                    ->can('update', 'variant');
                Route::post('/{product}/variant/{variant}/image', [ProductVariantImageController::class, 'store'])
                    ->name('product.variant.image.store')
                    ->can('create', Product::class);
                Route::delete('/{product}/variant/{variant}/image/{image}', [ProductVariantImageController::class, 'destroy'])
                    ->name('product.variant.image.destroy')
                    ->can('update', 'product');
            });
        });
        Route::prefix('order')->group(function () {
            Route::middleware(['auth:sanctum'])->group(function () {
                Route::get('/{order}', [OrderController::class, 'show'])
                    ->name('order.show');
                Route::put('/{order}/status', [OrderController::class, 'status'])
                    ->name('order.status.update')
                    ->can('update', 'order');
                Route::get('/{order}/invoice', [OrderInvoiceController::class, 'show'])
                    ->name('order.invoice.show');
            });
        });
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductsController::class, 'index'])
                ->name('products.index');
            Route::get('/search', [ProductsController::class, 'search'])
                ->name('products.search.index');
        });
        Route::prefix('token')->group(function () {
            Route::post('/', [TokenController::class, 'store'])
                ->name('token.store');
            Route::middleware(['auth:sanctum'])->group(function () {
                Route::delete('/', [TokenController::class, 'destroy'])
                    ->name('token.destroy');
            });
        });
        Route::fallback(function () {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        });
    });
