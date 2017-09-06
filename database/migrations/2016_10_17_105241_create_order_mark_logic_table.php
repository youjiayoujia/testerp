<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMarkLogicTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_mark_logic', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id');
			$table->string('order_status');
			$table->integer('order_create');
			$table->integer('order_pay');
			$table->enum('assign_shipping_logistics', array('1','2'))->default('1');
			$table->string('shipping_logistics_name');
			$table->enum('is_upload', array('1','2'))->default('1');
			$table->integer('user_id');
			$table->integer('priority');
			$table->enum('wish_upload_tracking_num', array('0','1'))->default('0');
			$table->enum('is_use', array('0','1'))->default('1');
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
        Schema::drop('order_mark_logic');
    }

}
