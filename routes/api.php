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
            Route::post('/', [CategoryController::class, 'store'])
                ->name('category.store');
            Route::get('/{category}', [CategoryController::class, 'show'])
                ->name('category.show');
            Route::put('/{category}', [CategoryController::class, 'update'])
                ->name('category.update');
            Route::get('/{category}/products', [CategoryProductController::class, 'index'])
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
