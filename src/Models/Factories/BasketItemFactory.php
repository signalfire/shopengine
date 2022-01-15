<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Signalfire\Shopengine\Models\BasketItem;

class BasketItemFactory extends Factory
{
    protected $model = BasketItem::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'quantity' => $this->faker->randomDigit()
        ];
    }
}
