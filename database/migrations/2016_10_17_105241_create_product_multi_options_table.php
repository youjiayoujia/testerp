<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMultiOptionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_multi_options', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id');
			$table->integer('channel_id');
			$table->string('en_name');
			$table->string('en_description');
			$table->string('en_keywords');
			$table->string('de_name');
			$table->string('de_description');
			$table->string('de_keywords');
			$table->string('fr_name');
			$table->string('fr_description');
			$table->string('fr_keywords');
			$table->string('it_name');
			$table->string('it_description');
			$table->string('it_keywords');
			$table->string('zh_name');
			$table->string('zh_description');
			$table->string('zh_keywords');
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
        Schema::drop('product_multi_options');
    }

}
