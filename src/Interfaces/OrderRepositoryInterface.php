<?php

namespace Signalfire\Shopengine\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\User;

interface OrderRepositoryInterface
{
    public function getUserOrders(User $user): Collection;
    public function updateOrderStatus(Order $order, Array $validated): Order;
}