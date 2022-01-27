<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Resources\OrderResource;
use Signalfire\Shopengine\Models\Order;

class OrderController extends Controller
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
        return (new OrderResource($order))
            ->response()
            ->setStatusCode(200);
    }
}
