<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemArrivalLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_item_arrival_logs', function(Blueprint $table) {
            $table->increments('id');
			$table->string('sku');
			$table->integer('purchase_item_id')->default(0);
			$table->integer('arrival_num')->default(0);
			$table->integer('good_num')->default(0);
			$table->integer('bad_num')->default(0);
			$table->dateTime('quality_time');
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
        Schema::drop('purchase_item_arrival_logs');
    }

}
