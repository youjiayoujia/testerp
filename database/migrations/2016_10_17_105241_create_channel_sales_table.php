<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelSalesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_sales', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id')->default(0);
			$table->string('channel_sku');
			$table->integer('quantity')->default(0);
			$table->integer('account_id')->default(0);
			$table->dateTime('create_time')->default('0000-00-00 00:00:00');
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
        Schema::drop('channel_sales');
    }

}
