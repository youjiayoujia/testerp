<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarryOverFormsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carry_over_forms', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->integer('stock_id')->default(0);
			$table->integer('begin_quantity')->default(0);
			$table->decimal('begin_amount', 16, 4)->default(0.0000);
			$table->integer('over_quantity')->default(0);
			$table->decimal('over_amount', 16, 4)->default(0.0000);
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
        Schema::drop('carry_over_forms');
    }

}
