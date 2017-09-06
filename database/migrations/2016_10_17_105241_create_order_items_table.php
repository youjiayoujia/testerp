<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('order_id');
			$table->integer('item_id');
			$table->string('sku');
			$table->string('channel_sku');
			$table->integer('quantity');
			$table->float('price');
			$table->string('currency');
			$table->enum('is_active', array('0','1'))->nullable()->default('1');
			$table->enum('status', array('NEW','PACKED','SHIPPED'))->nullable()->default('NEW');
			$table->enum('is_gift', array('0','1'))->nullable()->default('0');
			$table->enum('is_refund', array('0','1'))->default('0');
			$table->integer('split_quantity')->default(0);
			$table->string('transaction_id')->nullable();
			$table->string('channel_order_id')->nullable();
			$table->string('orders_item_number')->nullable();
			$table->string('remark')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('ebay_unpaid_status')->nullable();
			$table->integer('refund_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_items');
    }

}
