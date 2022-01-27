<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\User;

class AddressPolicy
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
     * Determine if the given address can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User    $user
     * @param Signalfire\Shopengine\Models\Address $address
     *
     * @return bool
     */
    public function update(User $user, Address $address)
    {
        return $user->isAdmin() || ($user->isCustomer() && $address->user_id === $user->id);
    }

    /**
     * Determine if a address can be deleted by user.
     *
     * @param Signalfire\Shopengine\Models\User    $user
     * @param Signalfire\Shopengine\Models\Address $address
     *
     * @return bool
     */
    public function delete(User $user, Address $address)
    {
        return $user->isAdmin() || ($user->isCustomer() && $address->user_id === $user->id);
    }
}
