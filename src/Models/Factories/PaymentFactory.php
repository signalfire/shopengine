<?php

namespace Signalfire\Shopengine\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalfire\Shopengine\Models\Payment;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'id'       => $this->faker->uuid(),
            'gateway' => $this->faker->randomElement(['paypal', 'stripe', 'square']),
            'reference' => $this->faker->lexify('txn???????????'),
            'total' => $this->faker->randomFloat(2, 5, 1000)
        ];
    }
}
