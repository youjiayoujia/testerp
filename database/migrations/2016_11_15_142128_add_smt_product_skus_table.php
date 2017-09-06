<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmtProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('smt_product_skus', function (Blueprint $table) {
            $table->integer('propertyValueId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smt_product_skus', function (Blueprint $table) {
            $table->dropColumn('propertyValueId');
        });
    }
}
