<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklist_items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('picklist_id')->default(0);
			$table->integer('item_id')->default(0);
			$table->integer('warehouse_position_id');
			$table->integer('quantity')->default(0);
			$table->enum('type', array('SINGLE','SINGLEMULTI','MULTI'))->default('SINGLE');
			$table->timestamps();
			$table->softDeletes();
			$table->string('sku');
			$table->integer('packed_quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('picklist_items');
    }

}
