<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePositionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_positions', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 128)->nullable();
			$table->integer('warehouse_id')->default(0);
			$table->text('remark', 65535);
			$table->enum('size', array('big','middle','small'))->default('middle');
			$table->float('length')->default(50.00);
			$table->float('width')->default(50.00);
			$table->float('height')->default(50.00);
			$table->enum('is_available', array('0','1'))->default('1');
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
        Schema::drop('warehouse_positions');
    }

}
