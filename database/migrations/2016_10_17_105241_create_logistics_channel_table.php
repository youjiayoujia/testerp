<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsChannelTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_channel', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('logistics_id');
			$table->integer('channel_id');
			$table->string('url')->nullable();
			$table->enum('is_up', array('0','1'))->nullable()->default('0');
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
        Schema::drop('logistics_channel');
    }

}
