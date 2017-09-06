<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_products', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->nullable();
			$table->string('choice_info')->nullable();
			$table->string('name')->nullable();
			$table->string('c_name')->nullable();
			$table->integer('supplier_id')->nullable();
			$table->string('supplier_info')->nullable();
			$table->string('supplier_sku')->nullable();
			$table->string('product_sale_url')->nullable();
			$table->float('purchase_price')->nullable();
			$table->float('purchase_carriage')->nullable();
			$table->float('weight')->nullable();
			$table->string('description')->nullable();
			$table->string('remark')->nullable();
			$table->string('image_remark')->nullable();
			$table->boolean('status')->nullable()->default(0);
			$table->boolean('edit_status')->nullable()->default(0);
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
        Schema::drop('ebay_products');
    }

}
