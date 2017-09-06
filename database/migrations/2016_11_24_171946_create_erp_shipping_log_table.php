<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpShippingLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_shipping_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->string('tracking_no');
            $table->string('logistics_channel_name');
            $table->dateTime('shipping_time')->default('0000-00-00 00:00:00');
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
        Schema::drop('erp_shipping_log');
    }
}
