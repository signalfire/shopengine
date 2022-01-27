<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Address;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'id'         => $this->faker->uuid(),
            'title'      => $this->faker->randomElement(['Mr', 'Mrs', 'Ms', 'Dr']),
            'forename'   => $this->faker->firstName(),
            'surname'    => $this->faker->lastName(),
            'address1'   => $this->faker->streetAddress(),
            'towncity'   => $this->faker->city(),
            'county'     => $this->faker->county(),
            'postalcode' => $this->faker->postcode(),
            'country'    => $this->faker->country(),
            'mobile'     => $this->faker->randomElement([$this->faker->phoneNumber(), null]),
            'phone'      => $this->faker->randomElement([$this->faker->phoneNumber(), null]),
            'email'      => $this->faker->safeEmail(),
        ];
    }
}
