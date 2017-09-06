<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPriceTaskTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_price_task', function(Blueprint $table) {
            $table->increments('id');
			$table->string('productID');
			$table->string('account');
			$table->boolean('status');
			$table->string('shipment_id');
			$table->integer('percentage');
			$table->float('re_pirce');
			$table->integer('main_id');
			$table->dateTime('api_time')->default('0000-00-00 00:00:00');
			$table->text('remark', 65535);
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
        Schema::drop('smt_price_task');
    }

}
