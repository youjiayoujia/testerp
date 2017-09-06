<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 128);
			$table->string('province');
			$table->string('city');
			$table->string('address');
			$table->integer('contact_by');
			$table->string('telephone');
			$table->integer('volumn');
			$table->enum('is_available', array('0','1'))->default('1');
			$table->timestamps();
			$table->softDeletes();
			$table->enum('type', array('local','oversea','third','fbaLocal'))->default('local');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('warehouses');
    }

}
