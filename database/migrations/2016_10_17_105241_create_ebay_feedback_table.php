<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayFeedbackTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_feedback', function(Blueprint $table) {
            $table->increments('id');
			$table->string('feedback_id');
			$table->integer('channel_account_id');
			$table->string('commenting_user');
			$table->integer('commenting_user_score');
			$table->string('comment_text');
			$table->string('comment_type');
			$table->string('ebay_item_id');
			$table->string('transaction_id');
			$table->dateTime('comment_time');
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
        Schema::drop('ebay_feedback');
    }

}
