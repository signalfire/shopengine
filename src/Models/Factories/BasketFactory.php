<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Basket;

class BasketFactory extends Factory
{
    protected $model = Basket::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
        ];
    }
}
