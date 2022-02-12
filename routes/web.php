<?php

use Signalfire\Shopengine\Http\Controllers\Web\HomeController;
use Signalfire\Shopengine\Http\Controllers\Web\OrderInvoiceController;

// @TODO - Change to permanent URL
Route::middleware('web')
    ->prefix('account')
    ->group(function () {
        Route::get('/order/{order}/invoice', [OrderInvoiceController::class, 'show'])
            ->name('account.invoice.show');
        //->can('view', 'order');
    });

Route::get('/', HomeController::class);
