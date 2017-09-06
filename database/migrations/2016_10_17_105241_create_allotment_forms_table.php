<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllotmentFormsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotment_forms', function(Blueprint $table) {
            $table->increments('id');
			$table->string('stock_allotment_id')->default('0');
			$table->integer('warehouse_position_id')->default(0);
			$table->integer('item_id')->default(0);
			$table->integer('quantity')->default(0);
			$table->float('amount')->default(0.00);
			$table->integer('receive_quantity')->default(0);
			$table->integer('in_warehouse_position_id')->default(0);
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
        Schema::drop('allotment_forms');
    }

}
