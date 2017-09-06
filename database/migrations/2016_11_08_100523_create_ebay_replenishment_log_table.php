<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayReplenishmentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_replenishment_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_id');
            $table->string('item_id');
            $table->string('sku');
            $table->integer('quantity');
            $table->string('remark');
            $table->string('is_mul');
            $table->string('is_api_success');
            $table->dateTime('update_time');
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
        Schema::drop('ebay_replenishment_log');
    }
}
