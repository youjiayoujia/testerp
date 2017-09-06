<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaypalDetailTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_paypal_detail', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('order_id');
			$table->integer('paypal_id');
			$table->string('paypal_account');
			$table->string('paypal_buyer_name');
			$table->string('paypal_address');
			$table->string('paypal_country');
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
        Schema::drop('order_paypal_detail');
    }

}
