<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantWarehouseLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_warehouse_location', function (Blueprint $table) {
            $table->uuid('warehouse_location_id');
            $table->uuid('product_variant_id');

            $table->foreign('warehouse_location_id', 'warehouse_location_id_foreign')->references('id')->on('warehouse_locations');
            $table->foreign('product_variant_id', 'product_variant_id_foreign')->references('id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_warehouse_location');
    }
}
