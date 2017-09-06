<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductGroupTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_group', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('token_id');
			$table->integer('group_id');
			$table->string('group_name');
			$table->integer('parent_id');
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
        Schema::drop('smt_product_group');
    }

}
