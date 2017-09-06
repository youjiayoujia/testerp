<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickPackageScoresTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_package_scores', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('picklist_id')->default(0);
			$table->integer('package_id')->default(0);
			$table->integer('package_score')->default(0);
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
        Schema::drop('pick_package_scores');
    }

}
