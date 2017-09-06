<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtFreightTemplateTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_freight_template', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('token_id');
			$table->integer('templateId');
			$table->string('templateName');
			$table->boolean('default')->default(0);
			$table->text('freightSettingList', 65535);
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
        Schema::drop('smt_freight_template');
    }

}
