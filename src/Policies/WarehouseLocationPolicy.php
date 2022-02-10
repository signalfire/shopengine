<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\WarehouseLocation;
use Signalfire\Shopengine\Models\User;

class WarehouseLocationPolicy
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
     * Determine if a location can be created by the user.
     *
     * @param Signalfire\Shopengine\Models\WarehouseLocation $location
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the given location can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User     $user
     * @param Signalfire\Shopengine\Models\WarehouseLocation $location
     *
     * @return bool
     */
    public function update(User $user, WarehouseLocation $location)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if a location can be deleted by user.
     *
     * @param Signalfire\Shopengine\Models\User     $user
     * @param Signalfire\Shopengine\Models\WarehouseLocation $location
     *
     * @return bool
     */
    public function delete(User $user, WarehouseLocation $location)
    {
        return $user->isAdmin();
    }
}
