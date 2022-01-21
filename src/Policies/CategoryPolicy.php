<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\User;
use Signalfire\Shopengine\Models\Role;

class CategoryPolicy
{
    /**
     * Determine if a category can be created by the user.
     *
     * @param  Signalfire\Shopengine\Models\User  $user
     * @return bool
     */

    public function create(User $user)
    {
        $admin = Role::where('name', 'admin')->first();
        return $user->roles->contains($admin);
    }

    /**
     * Determine if the given category can be updated by the user.
     *
     * @param  Signalfire\Shopengine\Models\User  $user
     * @param  Signalfire\Shopengine\Models\Category  $category
     * @return bool
     */
    public function update(User $user, Category $category)
    {
        $admin = Role::where('name', 'admin')->first();
        return $user->roles->contains($admin);
    }
}
