<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendEbayMessageListTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_ebay_message_list', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('operate_id');
			$table->integer('order_id');
			$table->string('title')->nullable();
			$table->text('content')->nullable();
			$table->enum('is_send', array('0','1'))->default('0');
			$table->string('itemids')->nullable();
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
        Schema::drop('send_ebay_message_list');
    }

}
