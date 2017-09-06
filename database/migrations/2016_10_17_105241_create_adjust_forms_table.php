<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustFormsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjust_forms', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('stock_adjustment_id')->default(0);
			$table->integer('item_id')->default(0);
			$table->enum('type', array('IN','OUT'))->default('IN');
			$table->integer('warehouse_position_id')->default(0);
			$table->integer('quantity')->default(0);
			$table->float('amount')->default(0.00);
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
        Schema::drop('adjust_forms');
    }

}
