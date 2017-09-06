<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierChangeHistorysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_change_historys', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('supplier_id')->default(0);
			$table->integer('from')->default(0);
			$table->integer('to')->default(0);
			$table->integer('adjust_by')->default(0);
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
        Schema::drop('supplier_change_historys');
    }

}
