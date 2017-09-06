<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsChannelNamesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_channel_names', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('channel_id')->default(0);
			$table->string('name');
			$table->timestamps();
			$table->softDeletes();
			$table->string('logistics_key');
			$table->string('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logistics_channel_names');
    }

}
