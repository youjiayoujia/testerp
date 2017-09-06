<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtTemplateTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_template', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('plat');
			$table->integer('token_id');
			$table->string('name');
			$table->string('pic_path');
			$table->text('content', 65535);
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
        Schema::drop('smt_template');
    }

}
