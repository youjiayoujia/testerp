<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdjustmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function(Blueprint $table) {
            $table->increments('id');
			$table->string('adjust_form_id')->default('0');
			$table->integer('warehouse_id')->default(0);
			$table->integer('adjust_by')->default(0);
			$table->text('remark', 65535);
			$table->integer('check_by')->default(0);
			$table->date('check_time')->default('0000-00-00');
			$table->enum('status', array('0','1','2'))->default('0');
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
        Schema::drop('stock_adjustments');
    }

}
