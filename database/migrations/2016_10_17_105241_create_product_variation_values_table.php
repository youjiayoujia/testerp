<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationValuesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_values', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->nullable()->default(0);
			$table->integer('variation_id')->nullable()->default(0);
			$table->integer('variation_value_id')->nullable()->default(0);
			$table->string('variation_value')->nullable();
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
        Schema::drop('product_variation_values');
    }

}
