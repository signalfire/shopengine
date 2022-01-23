<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\ProductVariant;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'id'     => $this->faker->uuid(),
            'name'   => $this->faker->word(),
            'slug'   => $this->faker->slug(),
            'stock'  => $this->faker->randomDigitNot(0),
            'price'  => $this->faker->randomFloat(2, 5, 100),
            'status' => 1,
        ];
    }
}
