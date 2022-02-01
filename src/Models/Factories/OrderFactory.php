<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'id'      => $this->faker->uuid(),
            'total'   => $this->faker->randomFloat(2, 5, 100),
            'gift'    => false,
            'terms'   => true,
            'printed' => false,
            'status'  => 1,
        ];
    }
}
