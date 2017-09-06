<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayPublishProductTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_publish_product', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('account_id');
			$table->string('item_id');
			$table->string('primary_category');
			$table->string('secondary_category');
			$table->string('title');
			$table->string('sub_title');
			$table->string('sku');
			$table->string('site_name');
			$table->string('site');
			$table->float('start_price');
			$table->integer('quantity');
			$table->float('reserve_price');
			$table->float('buy_it_now_price');
			$table->string('listing_type');
			$table->string('view_item_url');
			$table->string('listing_duration');
			$table->string('dispatch_time_max');
			$table->string('private_listing');
			$table->string('payment_methods');
			$table->string('paypal_email_address');
			$table->string('currency');
			$table->string('location');
			$table->string('postal_code');
			$table->integer('quantity_sold');
			$table->string('store_category_id');
			$table->string('condition_id');
			$table->string('condition_description');
			$table->text('picture_details', 65535);
			$table->text('item_specifics', 65535);
			$table->text('variation_picture', 65535);
			$table->text('variation_specifics', 65535);
			$table->text('return_policy', 65535);
			$table->text('shipping_details', 65535);
			$table->enum('status', array('0','1','2','3'))->default('0');
			$table->enum('is_out_control', array('0','1'))->default('0');
			$table->enum('multi_attribute', array('0','1'))->default('0');
			$table->string('seller_id');
			$table->text('description', 65535);
			$table->dateTime('start_time');
			$table->dateTime('update_time');
			$table->timestamps();
			$table->softDeletes();
			$table->text('buyer_requirement', 65535);
			$table->string('country')->default('');
			$table->integer('description_id')->default(0);
			$table->integer('warehouse')->default(1);
			$table->text('description_picture', 65535);
			$table->text('note', 65535);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ebay_publish_product');
    }

}
