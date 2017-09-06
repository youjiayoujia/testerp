<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAbnormalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_abnormals', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('spu_id')->nullable();
			$table->integer('type')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('image_id')->nullable();
			$table->integer('status')->nullable()->default(1);
			$table->integer('update_userId')->nullable();
			$table->string('remark')->nullable();
			$table->date('arrival_time')->nullable();
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
        Schema::drop('product_abnormals');
    }

}
