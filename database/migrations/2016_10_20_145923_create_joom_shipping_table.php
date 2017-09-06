<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoomShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joom_shipping', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('账号')->default('liufei@moonarstore.com');
            $table->integer('orderID')->comment('订单ID');
            $table->string('joomID')->comment("joom平台的ID号");
            $table->string('tracking_no')->comment('最后一次上传joom平台的追踪号');
            $table->integer('requestTime')->comment('请求时间');
            $table->integer('erp_orders_status')->comment('0不需要请求，1需要请求')->default('0');
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
        Schema::drop('joom_shipping');
    }
}
