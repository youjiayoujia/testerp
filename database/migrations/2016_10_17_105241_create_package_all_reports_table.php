<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageAllReportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_all_reports', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id')->default(0);
			$table->integer('warehouse_id')->default(0);
			$table->integer('wait_send')->default(0);
			$table->integer('sending')->default(0);
			$table->integer('sended')->default(0);
			$table->integer('more')->default(0);
			$table->integer('less')->default(0);
			$table->integer('daily_send')->default(0);
			$table->integer('need')->default(0);
			$table->decimal('daily_sales', 9)->default(0.00);
			$table->decimal('month_sales', 11)->default(0.00);
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
        Schema::drop('package_all_reports');
    }

}
