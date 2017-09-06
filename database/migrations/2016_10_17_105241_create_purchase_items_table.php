<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('type')->nullable();
			$table->integer('status')->default(0);
			$table->integer('storageStatus')->default(0);
			$table->integer('costExamineStatus')->default(0);
			$table->integer('item_id')->nullable();
			$table->integer('order_item_id')->nullable();
			$table->string('sku');
			$table->integer('supplier_id');
			$table->integer('purchase_num');
			$table->integer('arrival_num')->default(0);
			$table->integer('lack_num');
			$table->integer('warehouse_id');
			$table->integer('purchase_order_id');
			$table->float('postage', 15, 8);
			$table->string('post_coding')->default('0');
			$table->float('purchase_cost', 15, 8);
			$table->integer('active')->default(0);
			$table->integer('active_num')->nullable()->default(0);
			$table->integer('active_status')->default(0);
			$table->string('remark');
			$table->date('arrival_time');
			$table->integer('storage_qty')->nullable();
			$table->string('bar_code')->nullable();
			$table->integer('stock_id')->nullable();
			$table->date('start_buying_time')->nullable();
			$table->date('wait_time')->nullable();
			$table->string('wait_remark')->nullable();
			$table->integer('unqualified_qty');
			$table->integer('user_id');
			$table->integer('update_userid');
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
        Schema::drop('purchase_items');
    }

}
