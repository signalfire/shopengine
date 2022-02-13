<?php

namespace Signalfire\Shopengine\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Signalfire\Shopengine\Interfaces\OrderRepositoryInterface;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\User;

class OrderRepository implements OrderRepositoryInterface
{
    public function getUserOrders(User $user): Collection
    {
        return $user->orders()->get();
    }

    public function updateOrderStatus(Order $order, array $validated): Order
    {
        $order->update($validated);
        $order->refresh();

        return $order;
    }
}
