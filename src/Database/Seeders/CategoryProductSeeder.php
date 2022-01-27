<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Signalfire\Shopengine\Models\Category;
use Signalfire\Shopengine\Models\Product;

class CategoryProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        $products = Product::all();

        foreach($products as $product) {
            $random = $categories->random(rand(1,10))->pluck('id');
            $product->categories()->attach($random->all());
        }
        
    }
}