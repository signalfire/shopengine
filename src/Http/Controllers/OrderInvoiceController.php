<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Signalfire\Shopengine\Models\Order;

class OrderInvoiceController extends Controller
{
    /**
     * Gets order.
     *
     * @param Signalfire\Shopengine\Models\Order $order
     *
     * @return string JSON
     */
    public function show(Order $order)
    {
        $pdf = PDF::loadView('shopengine::pdf.invoice', ['order' => $order]);

        return $pdf->download('invoice.pdf');
    }
}
