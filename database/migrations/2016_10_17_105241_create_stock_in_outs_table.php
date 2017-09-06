<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInOutsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_outs', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('quantity')->default(0);
			$table->float('amount')->default(0.00);
			$table->string('inner_type')->default('0');
			$table->enum('outer_type', array('IN','OUT'))->default('IN');
			$table->string('relation_id', 64)->default('0');
			$table->integer('stock_id')->default(0);
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
        Schema::drop('stock_in_outs');
    }

}
