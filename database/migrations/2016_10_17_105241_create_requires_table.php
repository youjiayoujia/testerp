<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequiresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requires', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('package_id');
			$table->integer('purchase_item_id');
			$table->integer('warehouse_id');
			$table->integer('item_id');
			$table->integer('order_item_id');
			$table->string('sku');
			$table->integer('quantity');
			$table->enum('is_require', array('0','1'))->default('1');
			$table->text('remark', 65535);
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
        Schema::drop('requires');
    }

}
