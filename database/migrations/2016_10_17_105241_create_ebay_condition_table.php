<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayConditionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_condition', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('condition_id');
			$table->string('condition_name')->default('');
			$table->integer('category_id');
			$table->integer('site');
			$table->string('is_variations')->default('');
			$table->string('is_condition')->default('');
			$table->string('is_upc')->default('');
			$table->string('is_ean')->default('');
			$table->string('is_isbn')->default('');
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
        Schema::drop('ebay_condition');
    }

}
