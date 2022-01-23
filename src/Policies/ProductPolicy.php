<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\Product;
use Signalfire\Shopengine\Models\User;

class ProductPolicy
{
    /**
     * Determine if a product can be created by the user.
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
     * Determine if the given product can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User    $user
     * @param Signalfire\Shopengine\Models\Product $product
     *
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        return $user->isAdmin();
    }
}
