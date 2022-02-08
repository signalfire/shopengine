<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\User;

class OrderPolicy
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
     * Determine if a address can be created by the user.
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
     * Determine if the given order can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User  $user
     * @param Signalfire\Shopengine\Models\Order $order
     *
     * @return bool
     */
    public function update(User $user, Order $order)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if a order can be deleted by user.
     *
     * @param Signalfire\Shopengine\Models\User  $user
     * @param Signalfire\Shopengine\Models\Order $order
     *
     * @return bool
     */
    public function delete(User $user, Order $order)
    {
        return $user->isAdmin();
    }
}
