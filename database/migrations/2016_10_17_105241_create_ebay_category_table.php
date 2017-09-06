<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayCategoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_category', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('category_id');
			$table->string('best_offer')->default('');
			$table->string('auto_pay')->default('');
			$table->integer('category_level');
			$table->string('category_name')->default('');
			$table->integer('category_parent_id');
			$table->string('leaf_category')->default('');
			$table->integer('site');
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
        Schema::drop('ebay_category');
    }

}
