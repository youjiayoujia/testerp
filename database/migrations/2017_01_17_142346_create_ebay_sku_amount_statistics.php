<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySkuAmountStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_sku_amount_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel_name')->comment('平台');
            $table->integer('user_id')->comment('用户');
            $table->integer('prefix')->comment('刊登前缀');
            $table->decimal('january_sales', 10, 2)->comment('1月累计销售额');
            $table->decimal('profit_rate', 6, 4)->comment('平均利润率');
            $table->integer('january_publish')->comment('1月刊登');
            $table->integer('january_publish_quantity')->comment('1月刊登售出量');
            $table->decimal('january_publish_amount', 10, 2)->comment('1月刊登售出额');
            $table->decimal('january_publish_ratio', 4, 2)->comment('1月刊售比');
            $table->decimal('january_advertisement_rate', 6, 4)->comment('1月动销广告率');
            $table->decimal('sku_sell_rate', 6, 4)->comment('SKU动销率');
            $table->integer('yesterday_publish')->comment('昨日刊登');
            $table->string('created_date')->comment('日期');
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
        Schema::drop('ebay_sku_amount_statistics');
    }
}
