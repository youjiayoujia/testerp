<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayStoreCategoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_store_category', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('account_id')->default(0);
			$table->string('store_category')->default('');
			$table->string('store_category_name')->default('');
			$table->integer('level')->default(0);
			$table->string('category_parent')->default('');
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
        Schema::drop('ebay_store_category');
    }

}
