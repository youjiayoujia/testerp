<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPriceTaskMainTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_price_task_main', function(Blueprint $table) {
            $table->increments('id');
			$table->string('token_id');
			$table->string('shipment_id');
			$table->string('shipment_id_op');
			$table->float('percentage');
			$table->float('re_pirce');
			$table->boolean('status');
			$table->string('group');
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
        Schema::drop('smt_price_task_main');
    }

}
