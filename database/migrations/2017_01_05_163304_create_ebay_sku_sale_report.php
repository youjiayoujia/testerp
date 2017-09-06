<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySkuSaleReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_sku_sale_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->comment('SKU');
            $table->string('channel_name')->comment('平台');
            $table->string('site')->comment('站点');
            $table->integer('sale_different')->comment('相邻两周销量差');
            $table->decimal('sale_different_proportion', 4, 2)->comment('相邻两周销量差比例');
            $table->integer('one_sale')->comment('1天销量');
            $table->integer('seven_sale')->comment('7天销量');
            $table->integer('fourteen_sale')->comment('14天销量');
            $table->integer('thirty_sale')->comment('30天销量');
            $table->integer('ninety_sale')->comment('90天销量');
            $table->dateTime('created_time')->comment('SKU创建时间')->default('0000-00-00 00:00:00');
            $table->string('status')->comment('状态');
            $table->enum('is_warning', array('0','1'))->comment('预警')->default('1');
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
        Schema::drop('ebay_sku_sale_report');
    }
}
