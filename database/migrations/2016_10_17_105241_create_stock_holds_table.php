<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHoldsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_holds', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('quantity')->default(0);
			$table->string('type');
			$table->integer('relation_id')->default(0);
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
        Schema::drop('stock_holds');
    }

}
