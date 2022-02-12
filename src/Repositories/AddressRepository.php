<?php

namespace Signalfire\Shopengine\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Signalfire\Shopengine\Interfaces\AddressRepositoryInterface;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\User;

class AddressRepository implements AddressRepositoryInterface
{
    public function getAddresses(User $user): Collection
    {
        $addresses = $user->addresses()->get();

        return $addresses;
    }

    public function createAddress(User $user, array $validated): Address
    {
        $address = $user->addresses()->create($validated);

        return $address;
    }

    public function updateAddress(Address $address, array $validated): Address
    {
        $address->update($validated);
        $address->refresh();

        return $address;
    }

    public function deleteAddress(Address $address): Address
    {
        $address->delete();

        return $address;
    }
}
