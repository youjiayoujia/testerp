<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayTimingSetTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_timing_set', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->default('');
			$table->integer('account_id')->default(0);
			$table->integer('site')->default(0);
			$table->integer('warehouse')->default(1);
			$table->string('start_time')->default('');
			$table->string('end_time')->default('');
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
        Schema::drop('ebay_timing_set');
    }

}
