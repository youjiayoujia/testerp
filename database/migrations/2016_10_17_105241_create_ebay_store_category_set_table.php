<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayStoreCategorySetTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_store_category_set', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('site')->default(0);
			$table->integer('warehouse')->default(1);
			$table->integer('category')->default(0);
			$table->text('category_description', 65535);
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
        Schema::drop('ebay_store_category_set');
    }

}
