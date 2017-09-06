<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayPublishProductDetailTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_publish_product_detail', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('publish_id');
			$table->integer('product_id');
			$table->string('sku');
			$table->float('start_price');
			$table->integer('quantity');
			$table->string('erp_sku');
			$table->string('quantity_sold');
			$table->string('item_id');
			$table->string('seller_id');
			$table->enum('status', array('0','1'))->default('0');
			$table->dateTime('start_time');
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
        Schema::drop('ebay_publish_product_detail');
    }

}
