<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogCommandsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_commands', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('relation_id');
			$table->string('signature');
			$table->text('data');
			$table->string('description');
			$table->float('lasting');
			$table->integer('total');
			$table->string('result');
			$table->string('remark');
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
        Schema::drop('log_commands');
    }

}
