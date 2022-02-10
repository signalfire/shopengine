<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Warehouse;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition()
    {
        return [
            'id'    => $this->faker->uuid(),
            'name'  => $this->faker->word(),
            'notes' => $this->faker->randomElement([$this->faker->sentence(), null]),
        ];
    }
}
