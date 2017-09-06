<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesLogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_log', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('message_id')->nullable();
			$table->integer('assign_id')->nullable();
			$table->string('foruser')->nullable();
			$table->string('touser')->nullable();
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
        Schema::drop('messages_log');
    }

}
