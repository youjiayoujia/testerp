<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomesClearancesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customes_clearances', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->default(0);
			$table->string('cn_name');
			$table->integer('hs_code')->default(0);
			$table->string('unit')->default('0');
			$table->text('f_model', 65535);
			$table->enum('status', array('0','1'))->default('0');
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
        Schema::drop('customes_clearances');
    }

}
