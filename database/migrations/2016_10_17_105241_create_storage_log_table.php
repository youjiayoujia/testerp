<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageLogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_log', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('purchaseItemId')->nullable();
			$table->integer('storage_quantity')->default(0);
			$table->integer('user_id');
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
        Schema::drop('storage_log');
    }

}
