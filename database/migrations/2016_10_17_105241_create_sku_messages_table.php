<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuMessagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_messages', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('sku_id');
			$table->string('question_group');
			$table->string('image');
			$table->string('question');
			$table->dateTime('question_time')->default('0000-00-00 00:00:00');
			$table->integer('question_user');
			$table->string('answer');
			$table->dateTime('answer_date')->default('0000-00-00 00:00:00');
			$table->integer('answer_user');
			$table->string('extra_question');
			$table->string('status');
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
        Schema::drop('sku_messages');
    }

}
