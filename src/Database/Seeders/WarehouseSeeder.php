<?php

namespace Signalfire\Shopengine\Database\Seeders;

use Illuminate\Database\Seeder;
use Signalfire\Shopengine\Models\ProductVariant;
use Signalfire\Shopengine\Models\Warehouse;
use Signalfire\Shopengine\Models\WarehouseLocation;

class WarehouseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $warehouse = Warehouse::factory()->create();
        $locations = WarehouseLocation::factory()->state([
            'warehouse_id' => $warehouse->id,
        ])->count(5)->create();
        $variants = ProductVariant::all();
        foreach ($variants as $variant) {
            $selected = $locations->random();
            $variant->locations()->attach($selected);
        }
    }
}
