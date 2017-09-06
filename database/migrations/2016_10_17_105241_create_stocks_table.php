<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('item_id');
			$table->integer('warehouse_id');
			$table->integer('warehouse_position_id');
			$table->integer('all_quantity');
			$table->integer('available_quantity');
			$table->integer('hold_quantity');
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
        Schema::drop('stocks');
    }

}
