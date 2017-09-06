<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickReportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_reports', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->default(0);
			$table->integer('warehouse_id')->default(0);
			$table->integer('single')->default(0);
			$table->integer('singleMulti')->default(0);
			$table->integer('multi')->default(0);
			$table->integer('missing_pick')->default(0);
			$table->integer('today_pick')->default(0);
			$table->integer('today_picklist')->default(0);
			$table->dateTime('day_time')->default('0000-00-00 00:00:00');
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
        Schema::drop('pick_reports');
    }

}
