<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpGzAddressTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_gz_address', function(Blueprint $table) {
            $table->increments('id');
			$table->string('sender');
			$table->string('address');
			$table->boolean('useNumber');
			$table->date('updateTime');
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
        Schema::drop('erp_gz_address');
    }

}
