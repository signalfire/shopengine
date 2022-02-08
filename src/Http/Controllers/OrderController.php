<?php

namespace Signalfire\Shopengine\Http\Controllers;

use Signalfire\Shopengine\Http\Resources\OrderResource;
use Signalfire\Shopengine\Http\Requests\UpdateOrderStatusRequest;
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

    /**
     * Updates existing order status.
     *
     * @param UpdateOrderStatusRequest $request
     * @param Signalfire\Shopengine\Models\Order $order
     *
     * @return string JSON
     */
    public function status(UpdateOrderStatusRequest $request, Order $order)
    {
        $validated = $request->validated();

        $validated['dispatched_at'] = now();

        $order->update($validated);

        $order->refresh();

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(204);
    }    
}
