<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('cardholder_address_id');
            $table->uuid('delivery_address_id');
            $table->decimal('total', 10, 2);
            $table->boolean('gift');
            $table->boolean('terms');
            $table->tinyInteger('status')->index();
            $table->timestampTz('dispatched_at')->nullable();
            $table->timestampsTz();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cardholder_address_id')->references('id')->on('addresses');
            $table->foreign('delivery_address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
