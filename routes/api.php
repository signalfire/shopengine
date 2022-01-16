<?php

use Signalfire\Shopengine\Http\Controllers\BasketController;
use Signalfire\Shopengine\Http\Controllers\BasketItemController;
use Signalfire\Shopengine\Http\Controllers\CategoryController;
use Signalfire\Shopengine\Http\Controllers\ProductController;

Route::middleware('api')
    ->prefix('api')
    ->group(function () {
        Route::prefix('basket')->group(function () {
            Route::post('/', [BasketController::class, 'store'])
                ->name('basket.store');
            Route::get('/{basket_id}', [BasketController::class, 'show'])
                ->where(
                    'basket_id',
                    '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
                )
                ->name('basket.show');
            Route::delete('/{basket_id}', [BasketController::class, 'destroy'])
                ->where(
                    'basket_id',
                    '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
                )
                ->name('basket.destroy');
            Route::post('/{basket_id}/items', [BasketItemController::class, 'store'])
                ->where(
                    'basket_id',
                    '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
                )
                ->name('basket.item.store');
            Route::delete('/{basket_id}/items', [BasketItemController::class, 'destroy'])
                ->where(
                    'basket_id',
                    '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
                )
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
                ->where(
                    'category_id',
                    '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
                )
                ->name('category.update');
        });
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])
                ->name('products.index');
        });
    });
