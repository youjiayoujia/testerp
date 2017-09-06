<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id');
			$table->integer('warehouse_position_id');
			$table->integer('package_id');
			$table->integer('order_item_id');
			$table->integer('quantity');
			$table->integer('picked_quantity');
			$table->text('remark', 65535);
			$table->enum('is_mark', array('0','1'))->default('0');
			$table->enum('is_upload', array('0','1','2'))->default('0');
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
        Schema::drop('package_items');
    }

}
