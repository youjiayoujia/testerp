<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTakingFormsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_taking_forms', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('stock_taking_id')->default(0);
			$table->integer('stock_id')->default(0);
			$table->string('quantity');
			$table->enum('stock_taking_status', array('more','equal','less'))->default('equal');
			$table->enum('stock_taking_yn', array('0','1'))->default('0');
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
        Schema::drop('stock_taking_forms');
    }

}
