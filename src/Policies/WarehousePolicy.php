<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\Warehouse;
use Signalfire\Shopengine\Models\User;

class WarehousePolicy
{
    /**
     * Determine if a warehouse can be viewed by user.
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
     * Determine if a category can be created by the user.
     *
     * @param Signalfire\Shopengine\Models\Warehouse $warehouse
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the given warehouse can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User     $user
     * @param Signalfire\Shopengine\Models\Warehouse $warehouse
     *
     * @return bool
     */
    public function update(User $user, Warehouse $warehouse)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if a warehouse can be deleted by user.
     *
     * @param Signalfire\Shopengine\Models\User     $user
     * @param Signalfire\Shopengine\Models\Warehouse $warehouse
     *
     * @return bool
     */
    public function delete(User $user, Warehouse $warehouse)
    {
        return $user->isAdmin();
    }
}
