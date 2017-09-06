<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsCodesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_codes', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('logistics_id');
			$table->string('code')->default('');
			$table->integer('package_id')->nullable();
			$table->enum('status', array('0','1'));
			$table->date('used_at')->nullable();
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
        Schema::drop('logistics_codes');
    }

}
