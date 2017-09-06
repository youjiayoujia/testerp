<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayShippingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_shipping', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('site_id')->default(0);
			$table->string('description')->default('');
			$table->enum('international_service', array('1','2'))->default('1');
			$table->string('shipping_service')->default('');
			$table->integer('shipping_service_id')->default(0);
			$table->integer('shipping_time_max')->default(0);
			$table->integer('shipping_time_min')->default(0);
			$table->enum('valid_for_selling_flow', array('1','2'))->default('1');
			$table->string('shipping_category')->default('');
			$table->string('shipping_carrier')->default('');
			$table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ebay_shipping');
    }

}
