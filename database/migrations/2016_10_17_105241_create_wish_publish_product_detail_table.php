<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWishPublishProductDetailTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wish_publish_product_detail', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id');
			$table->integer('account_id');
			$table->string('productID');
			$table->string('product_sku_id');
			$table->string('sku');
			$table->string('erp_sku');
			$table->string('sellerID');
			$table->string('price');
			$table->string('inventory');
			$table->string('color');
			$table->string('size');
			$table->string('shipping');
			$table->string('msrp');
			$table->string('shipping_time');
			$table->string('main_image');
			$table->string('enabled');
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
        Schema::drop('wish_publish_product_detail');
    }

}
