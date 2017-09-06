<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklists', function(Blueprint $table) {
            $table->increments('id');
			$table->string('picknum');
			$table->enum('type', array('SINGLE','SINGLEMULTI','MULTI'))->default('SINGLE');
			$table->enum('status', array('NONE','PRINTED','PICKING','PICKED','INBOXING','INBOXED','PACKAGEING','PACKAGED'))->default('NONE');
			$table->integer('logistic_id')->default(0);
			$table->integer('pick_by')->default(0);
			$table->dateTime('print_at')->default('0000-00-00 00:00:00');
			$table->dateTime('pick_at')->default('0000-00-00 00:00:00');
			$table->integer('inbox_by');
			$table->dateTime('inbox_at')->default('0000-00-00 00:00:00');
			$table->integer('pack_by');
			$table->dateTime('pack_at')->default('0000-00-00 00:00:00');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('picklists');
    }

}
