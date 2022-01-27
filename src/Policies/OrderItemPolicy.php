<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\OrderItem;
use Signalfire\Shopengine\Models\User;

class OrderItemPolicy
{
    /**
     * Determine if a address can be viewed by user.
     *
     * @param Signalfire\Shopengine\Models\User $user
     *
     * @return bool
     */
    public function view(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if a order item can be created by the user.
     *
     * @param Signalfire\Shopengine\Models\User $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isCustomer();
    }

    /**
     * Determine if the given order item can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User  $user
     * @param Signalfire\Shopengine\Models\OrderItem $item
     *
     * @return bool
     */
    public function update(User $user, OrderItem $item)
    {
        return $user->isAdmin() || ($user->isCustomer() && $item->order->user_id === $user->id);
    }

    /**
     * Determine if a order item can be deleted by user.
     *
     * @param Signalfire\Shopengine\Models\User  $user
     * @param Signalfire\Shopengine\Models\OrderItem $item
     *
     * @return bool
     */
    public function delete(User $user, OrderItem $order)
    {
        return $user->isAdmin() || ($user->isCustomer() && $item->order->user_id === $user->id);
    }
}
