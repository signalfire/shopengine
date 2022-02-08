<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Signalfire\Shopengine\Models\Order;
use Signalfire\Shopengine\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();

        foreach($orders as $order) {
            Payment::factory()->state([
                'order_id' => $order->id,
                'total' => $order->total
            ])->create();
        }
    }
}