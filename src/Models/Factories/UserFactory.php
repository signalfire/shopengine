<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Signalfire\Shopengine\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'id'                => $this->faker->uuid(),
            'name'              => $this->faker->name(),
            'email'             => $this->faker->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make($this->faker->password()),
            'mobile' => $this->faker->randomElement([$this->faker->phoneNumber(), null]),
            'phone' => $this->faker->randomElement([$this->faker->phoneNumber(), null])
        ];
    }
}
