<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderUnpaidsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_unpaids', function(Blueprint $table) {
            $table->increments('id');
			$table->string('ordernum');
			$table->string('remark');
			$table->string('note');
			$table->date('date');
			$table->integer('channel_id');
			$table->integer('customer_id');
			$table->enum('status', array('PERFORM','NOT_PERFORM','CONFIRM'))->default('CONFIRM');
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
        Schema::drop('order_unpaids');
    }

}
