<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRatesChannelsCatalogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_rates_channels_catalogs', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('catalog_id');
			$table->integer('channel_id');
			$table->decimal('flat_rate', 6)->default(0.00);
			$table->decimal('rate', 6);
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
        Schema::drop('catalog_rates_channels_catalogs');
    }

}
