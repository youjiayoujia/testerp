<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBlacklistsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_blacklists', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id');
			$table->string('ordernum');
			$table->string('name');
			$table->string('email');
			$table->string('by_id');
			$table->string('zipcode');
			$table->string('channel_account');
			$table->enum('type', array('CONFIRMED','SUSPECTED','WHITE'))->default('SUSPECTED');
			$table->text('remark')->nullable();
			$table->integer('total_order');
			$table->integer('refund_order');
			$table->string('refund_rate');
			$table->string('color');
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
        Schema::drop('order_blacklists');
    }

}
