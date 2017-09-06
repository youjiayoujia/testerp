<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTakingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_takings', function(Blueprint $table) {
            $table->increments('id');
			$table->string('taking_id')->default('0');
			$table->integer('stock_taking_by')->default(0);
			$table->dateTime('stock_taking_time')->default('0000-00-00 00:00:00');
			$table->integer('adjustment_by')->default(0);
			$table->dateTime('adjustment_time')->default('0000-00-00 00:00:00');
			$table->integer('check_by')->default(0);
			$table->integer('create_status')->default(0);
			$table->enum('check_status', array('0','1','2'))->default('0');
			$table->dateTime('check_time')->default('0000-00-00 00:00:00');
			$table->enum('create_taking_adjustment', array('1','0'))->default('0');
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
        Schema::drop('stock_takings');
    }

}
