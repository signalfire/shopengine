<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Signalfire\Shopengine\Http\Requests\UpdateOrderStatusRequest;
use Signalfire\Shopengine\Http\Resources\OrderResource;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Interfaces\OrderRepositoryInterface;

class OrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

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
     * @param UpdateOrderStatusRequest           $request
     * @param Signalfire\Shopengine\Models\Order $order
     *
     * @return string JSON
     */
    public function status(UpdateOrderStatusRequest $request, Order $order)
    {
        $order = $this->orderRepository->updateOrderStatus($order, $request->validated());

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(204);
    }
}
