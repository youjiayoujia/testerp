<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductModuleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_module', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('module_id');
			$table->integer('token_id');
			$table->string('module_name');
			$table->string('module_type');
			$table->string('module_status');
			$table->string('aliMemberId');
			$table->text('displayContent', 65535);
			$table->text('moduleContents', 65535);
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
        Schema::drop('smt_product_module');
    }

}
