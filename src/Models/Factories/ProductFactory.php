<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'id'     => $this->faker->uuid(),
            'name'   => $this->faker->word(),
            'slug'   => $this->faker->slug(),
            'status' => 1,
        ];
    }
}
