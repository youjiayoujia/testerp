<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id')->default(0);
			$table->string('sku');
			$table->string('c_name');
			$table->integer('all_quantity')->default(0);
			$table->integer('available_quantity')->default(0);
			$table->integer('zaitu_num')->default(0);
			$table->integer('seven_sales')->default(0);
			$table->integer('fourteen_sales')->default(0);
			$table->integer('thirty_sales')->default(0);
			$table->string('thrend');
			$table->integer('need_purchase_num')->default(0);
			$table->decimal('refund_rate', 7)->default(0.00);
			$table->decimal('profit', 7)->default(0.00);
			$table->integer('owe_day');
			$table->string('status');
			$table->integer('user_id')->nullable();
			$table->enum('require_create', array('0','1'))->nullable()->default('0');
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
        Schema::drop('purchases');
    }

}
