<?php

use Signalfire\Shopengine\Http\Controllers\BasketController;
use Signalfire\Shopengine\Http\Controllers\BasketItemController;
use Signalfire\Shopengine\Http\Controllers\CategoryController;
use Signalfire\Shopengine\Http\Controllers\CategoryProductController;
use Signalfire\Shopengine\Http\Controllers\ProductController;
use Signalfire\Shopengine\Http\Controllers\ProductSearchController;

Route::middleware(['api'])
    ->prefix('api')
    ->group(function () {
        Route::prefix('basket')->group(function () {
            Route::post('/', [BasketController::class, 'store'])
                ->name('basket.store');
            Route::get('/{basket_id}', [BasketController::class, 'show'])
                ->whereUuid('basket_id')
                ->name('basket.show');
            Route::delete('/{basket_id}', [BasketController::class, 'destroy'])
                ->whereUuid('basket_id')
                ->name('basket.destroy');
            Route::post('/{basket_id}/items', [BasketItemController::class, 'store'])
                ->whereUuid('basket_id')
                ->name('basket.item.store');
            Route::delete('/{basket_id}/items', [BasketItemController::class, 'destroy'])
                ->whereUuid('basket_id')
                ->name('basket.item.destroy');
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])
                ->name('categories.index');
        });
        Route::prefix('category')->group(function () {
            Route::post('/', [CategoryController::class, 'store'])
                ->name('category.store');
            Route::put('/{category_id}', [CategoryController::class, 'update'])
                ->whereUuid('category_id')
                ->name('category.update');
            Route::get('/{category_id}', [CategoryController::class, 'showById'])
                ->whereUuid('category_id')
                ->name('category.show.id');
            Route::get('/{slug}', [CategoryController::class, 'showBySlug'])
                ->name('category.show.slug');
            Route::get('/{category_id}/products', [CategoryProductController::class, 'index'])
                ->whereUuid('category_id')
                ->name('category.products.index');
        });
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])
                ->name('products.index');
            Route::get('/search', [ProductSearchController::class, 'index'])
                ->name('product.search.index');
        });
        Route::fallback(function () {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        });
    });
