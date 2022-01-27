<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create();

        $cardholder = Address::factory()->state([
            'user_id' => $user->id,
        ])->create();

        $delivery = Address::factory()->state([
            'user_id' => $user->id,
        ])->create();

        Order::factory()->state([
            'user_id'               => $user->id,
            'cardholder_address_id' => $cardholder->id,
            'delivery_address_id'   => $delivery->id,
        ])->count(5)->create();
    }
}
