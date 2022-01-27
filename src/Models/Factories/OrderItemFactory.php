<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\OrderItem;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'id'    => $this->faker->uuid(),
            'quantity' => $this->faker->randomDigit(),
            'price' => $this->faker->randomFloat(2, 5, 100)
        ];
    }
}
