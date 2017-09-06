<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpLazadaProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_lazada_products', function(Blueprint $table) {
            $table->increments('id');
			$table->string('sellerSku');
			$table->string('shopSku');
			$table->string('sku');
			$table->string('name');
			$table->string('variation');
			$table->boolean('quantity');
			$table->float('price');
			$table->float('salePrice');
			$table->dateTime('saleStartDate');
			$table->dateTime('saleEndDate');
			$table->string('status');
			$table->string('productId');
			$table->string('account');
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
        Schema::drop('erp_lazada_products');
    }

}
