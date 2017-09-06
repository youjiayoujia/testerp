<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseaReportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_reports', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('account_id')->default(0);
			$table->string('plan_id')->default('0');
			$table->string('shipment_id')->default('0');
			$table->string('reference_id')->default('0');
			$table->string('shipment_name');
			$table->enum('status', array('NEW','PASS','FAIL','PICKING','PACKING','PACKED','SHIPPED'))->default('NEW');
			$table->enum('print_status', array('UNPRINT','PRINTED'))->default('UNPRINT');
			$table->string('inStock_status');
			$table->string('shipping_firstname');
			$table->string('shipping_lastname');
			$table->string('shipping_address');
			$table->string('shipping_city');
			$table->string('shipping_state');
			$table->string('shipping_country');
			$table->string('shipping_zipcode');
			$table->string('shipping_phone');
			$table->integer('quantity');
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
        Schema::drop('oversea_reports');
    }

}
