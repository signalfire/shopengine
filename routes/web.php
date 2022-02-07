<?php

use Signalfire\Shopengine\Http\Controllers\OrderInvoiceController;

// @TODO - Change to permanent URL
Route::middleware('web')
    ->prefix('account')
    ->group(function () {
        Route::get('/order/{order}/invoice', [OrderInvoiceController::class, 'show'])
            ->name('account.invoice.show');
        //->can('view', 'order');
    });
