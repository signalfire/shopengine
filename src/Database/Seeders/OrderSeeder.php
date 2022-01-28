<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Signalfire\Shopengine\Models\Address;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\OrderItem;
use Signalfire\Shopengine\Models\ProductVariant;
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

        $orders = Order::factory()->state([
            'user_id'               => $user->id,
            'cardholder_address_id' => $cardholder->id,
            'delivery_address_id'   => $delivery->id,
        ])->count(5)->create();

        foreach ($orders as $order) {
            $variants = ProductVariant::all()->take(rand(1, 5));
            foreach ($variants as $variant) {
                $items = OrderItem::factory()->state([
                    'order_id'           => $order->id,
                    'product_variant_id' => $variant->id,
                ])->create();
            }
        }
    }
}
