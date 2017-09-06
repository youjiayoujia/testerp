<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtCategoryListTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_category_list', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('category_id');
			$table->string('category_name');
			$table->string('category_name_en');
			$table->integer('pid');
			$table->boolean('level')->default(0);
			$table->boolean('isleaf')->default(0);
			$table->dateTime('last_update_time');
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
        Schema::drop('smt_category_list');
    }

}
