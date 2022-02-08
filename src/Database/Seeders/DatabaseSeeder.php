<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            CategoryProductSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
