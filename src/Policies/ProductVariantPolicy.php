<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\User;

class ProductVariantPolicy
{
    /**
     * Determine if a product variant can be created by the user.
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
     * Determine if the given product variant can be updated by the user.
     *
     * @param Signalfire\Shopengine\Models\User    $user
     * @param Signalfire\Shopengine\Models\ProductVariant $variant
     *
     * @return bool
     */
    public function update(User $user, ProductVariant $variant)
    {
        return $user->isAdmin();
    }
}
