<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'name'   => $this->faker->name(),
        ];
    }
}
