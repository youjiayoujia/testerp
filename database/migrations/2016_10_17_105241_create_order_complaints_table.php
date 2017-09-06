<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderComplaintsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_complaints', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('order_item_id')->nullable();
			$table->integer('create_user_id');
			$table->string('complaint_email');
			$table->integer('complaint_type')->default(0);
			$table->string('question')->nullable();
			$table->string('complaint_country')->nullable();
			$table->integer('update_user_id');
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
        Schema::drop('order_complaints');
    }

}
