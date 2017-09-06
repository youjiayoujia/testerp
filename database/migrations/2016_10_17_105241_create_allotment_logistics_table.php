<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllotmentLogisticsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotment_logistics', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('allotment_id');
			$table->string('type');
			$table->string('code');
			$table->float('fee');
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
        Schema::drop('allotment_logistics');
    }

}
