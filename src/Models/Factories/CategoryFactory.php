<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'id'          => $this->faker->uuid(),
            'name'        => $this->faker->word(),
            'slug'        => $this->faker->slug(),
            'description' => $this->faker->randomElement([$this->faker->sentence(), null]),
            'status'      => 1,
        ];
    }
}
