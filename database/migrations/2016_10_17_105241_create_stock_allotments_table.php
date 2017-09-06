<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAllotmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_allotments', function(Blueprint $table) {
            $table->increments('id');
			$table->string('allotment_id');
			$table->integer('out_warehouse_id')->default(0);
			$table->integer('in_warehouse_id')->default(0);
			$table->text('remark', 65535);
			$table->integer('allotment_by')->default(0);
			$table->enum('allotment_status', array('new','pick','out','check','over'))->default('new');
			$table->integer('check_by')->default(0);
			$table->enum('check_status', array('0','1','2'))->default('0');
			$table->date('check_time');
			$table->integer('checkform_by')->default(0);
			$table->date('checkform_time');
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
        Schema::drop('stock_allotments');
    }

}
