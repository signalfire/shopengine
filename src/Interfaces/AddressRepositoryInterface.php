<?php

namespace Signalfire\Shopengine\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\User;

interface AddressRepositoryInterface
{
    public function getAddresses(User $user): Collection;

    public function createAddress(User $user, array $validated): Address;

    public function updateAddress(Address $address, array $validated): Address;

    public function deleteAddress(Address $address): Address;
}
