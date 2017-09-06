<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsRuleCatalogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_rule_catalogs', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('logistics_rule_id')->default(0);
			$table->integer('catalog_id')->default(0);
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
        Schema::drop('logistics_rule_catalogs');
    }

}
