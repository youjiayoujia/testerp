<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtCategoryAttributeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_category_attribute', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('category_id');
			$table->mediumText('attribute');
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
        Schema::drop('smt_category_attribute');
    }

}
