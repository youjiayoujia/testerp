<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZonesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zones', function(Blueprint $table) {
            $table->increments('id');
			$table->string('zone');
			$table->integer('logistics_id');
			$table->enum('type', array('first','second'))->default('first');
			$table->decimal('fixed_weight', 7, 5)->nullable();
			$table->decimal('fixed_price', 7, 5)->nullable();
			$table->decimal('continued_weight', 7, 5)->nullable();
			$table->decimal('continued_price', 7, 5)->nullable();
			$table->decimal('other_fixed_price', 7, 5)->nullable();
			$table->decimal('discount', 7, 5);
			$table->enum('discount_weather_all', array('0','1'))->default('0');
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
        Schema::drop('logistics_zones');
    }

}
