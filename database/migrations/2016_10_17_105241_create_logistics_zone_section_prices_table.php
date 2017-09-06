<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZoneSectionPricesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zone_section_prices', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('logistics_zone_id')->default(0);
			$table->decimal('weight_from', 7, 5);
			$table->decimal('weight_to', 7, 5);
			$table->decimal('price', 7, 5);
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
        Schema::drop('logistics_zone_section_prices');
    }

}
