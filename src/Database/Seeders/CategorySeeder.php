<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Signalfire\Shopengine\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(10)->create();   
    }
}