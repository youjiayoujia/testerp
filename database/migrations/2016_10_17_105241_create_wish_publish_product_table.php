<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWishPublishProductTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wish_publish_product', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('account_id');
			$table->string('productID');
			$table->dateTime('publishedTime');
			$table->string('status');
			$table->enum('is_promoted', array('0','1'))->default('0');
			$table->string('review_status');
			$table->string('sellerID');
			$table->text('product_description', 65535);
			$table->string('product_name');
			$table->string('parent_sku');
			$table->string('tags');
			$table->enum('product_type_status', array('1','2','3'))->default('1');
			$table->string('brand');
			$table->string('landing_page_url');
			$table->string('upc');
			$table->string('number_saves');
			$table->string('number_sold');
			$table->text('extra_images', 65535);
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
        Schema::drop('wish_publish_product');
    }

}
