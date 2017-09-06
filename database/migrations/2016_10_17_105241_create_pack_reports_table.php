<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackReportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pack_reports', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->default(0);
			$table->integer('warehouse_id')->default(0);
			$table->integer('yesterday_send')->default(0);
			$table->integer('single')->default(0);
			$table->integer('singleMulti')->default(0);
			$table->integer('multi')->default(0);
			$table->integer('all_worktime')->default(0);
			$table->integer('error_send')->default(0);
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
        Schema::drop('pack_reports');
    }

}
