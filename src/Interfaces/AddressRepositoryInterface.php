<?php

namespace Signalfire\Shopengine\Interfaces;

use Illuminate\Database\Eloquent\Collection;

use Signalfire\Shopengine\Models\User;
use Signalfire\Shopengine\Models\Address;

interface AddressRepositoryInterface
{
    public function getAddresses(User $user): Collection;
    public function createAddress(User $user, Array $validated): Address;
    public function updateAddress(Address $address, Array $validated): Address;
    public function deleteAddress(Array $validated): Address;
}