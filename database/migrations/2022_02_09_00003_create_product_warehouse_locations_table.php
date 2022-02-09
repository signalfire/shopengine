<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehouseLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warehouse_locations', function (Blueprint $table) {
            $table->uuid('warehouse_location_id');
            $table->uuid('product_variant_id');
            $table->unsignedBigInteger('stock');

            $table->foreign('warehouse_location_id')->references('id')->on('warehouse_locations');
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_warehouse_locations');
    }
}
