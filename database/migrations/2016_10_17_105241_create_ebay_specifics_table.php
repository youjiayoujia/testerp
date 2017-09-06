<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySpecificsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_specifics', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name')->default('');
			$table->integer('category_id');
			$table->integer('site');
			$table->string('value_type')->default('');
			$table->string('min_values')->default('');
			$table->string('max_values')->default('');
			$table->string('selection_mode')->default('');
			$table->string('variation_specifics')->default('');
			$table->text('specific_values', 65535);
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
        Schema::drop('ebay_specifics');
    }

}
