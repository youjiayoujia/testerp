<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFeatureValuesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_feature_values', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->nullable()->default(0);
			$table->integer('feature_id')->nullable()->default(0);
			$table->integer('feature_value_id')->nullable()->default(0);
			$table->string('feature_value')->nullable();
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
        Schema::drop('product_feature_values');
    }

}
