<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spus', function(Blueprint $table) {
            $table->increments('id');
			$table->string('spu')->nullable();
			$table->integer('product_require_id')->nullable();
			$table->integer('purchase')->nullable();
			$table->integer('edit_user')->nullable();
			$table->integer('image_edit')->nullable();
			$table->integer('developer')->nullable();
			$table->string('status')->nullable()->default('0');
			$table->string('remark')->nullable();
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
        Schema::drop('spus');
    }

}
