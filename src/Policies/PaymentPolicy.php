<?php

namespace Signalfire\Shopengine\Policies;

use Signalfire\Shopengine\Models\User;

class PaymentPolicy
{
    /**
     * Determine if a payment can be viewed by user.
     *
     * @param Signalfire\Shopengine\Models\User $user
     *
     * @return bool
     */
    public function view(User $user)
    {
        return $user->isAdmin();
    }
}
