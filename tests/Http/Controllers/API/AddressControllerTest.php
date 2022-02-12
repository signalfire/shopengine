<?php

namespace Signalfire\Shopengine\Tests;

use Laravel\Sanctum\Sanctum;

use Signalfire\Shopengine\Models\User;
use Signalfire\Shopengine\Models\Role;
use Signalfire\Shopengine\Models\Address;

class AddressControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->role = Role::factory()->state([
            'name' => 'customer',
        ])->create();
        $this->user->roles()->attach($this->role);
    }

    public function testGetUserAddresses()
    {
        Sanctum::actingAs($this->user);

        $addresses = Address::factory()->state([
            'user_id' => $this->user->id
        ])->count(2)->create();

        $this
            ->get(route('addresses.index'))
            ->assertJsonCount(2, 'data')
            ->assertStatus(200);

        $this->assertDatabaseCount('addresses', 2);
    }

    public function testCreateUserAddress()
    {
        Sanctum::actingAs($this->user);

        $address = Address::factory()->make();

        $this
            ->post(route('address.store'), [
                'title' => $address->title,
                'forename' => $address->forename,
                'surname' => $address->surname,
                'address1' => $address->address1,
                'towncity' => $address->towncity,
                'county' => $address->county,
                'postalcode' => $address->postalcode,
                'country' => $address->country
            ])
            ->assertStatus(201);
    }

    public function testUpdateUserAddress()
    {
        Sanctum::actingAs($this->user);

        $address1 = Address::factory()->state([
            'user_id' => $this->user->id
        ])->create();

        $address2 = Address::factory()->state([
            'user_id' => $this->user->id
        ])->make();

        $this
            ->put(route('address.update', ['address' => $address1->id]), [
                'title' => $address2->title,
                'forename' => $address2->forename,
                'surname' => $address2->surname,
                'address1' => $address2->address1,
                'towncity' => $address2->towncity,
                'county' => $address2->county,
                'postalcode' => $address2->postalcode,
                'country' => $address2->country
            ])
            ->assertStatus(204);
    }    
}