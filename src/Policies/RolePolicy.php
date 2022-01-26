<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\User;

class RolePolicy
{
    /**
     * Determine if a role can be viewed by user.
     *
     * @param Signalfire\Shopengine\Models\User $user
     *
     * @return bool
     */
    public function view(User $user){
        return $user->isAdmin();
    }

    /**
     * Determine if a role can be created by the user.
     *
     * @param Signalfire\Shopengine\Models\User $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the given role can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User $user
     * @param Signalfire\Shopengine\Models\Role $role
     *
     * @return bool
     */
    public function update(User $user, Role $role)
    {
        return $user->isAdmin();
    }
}
