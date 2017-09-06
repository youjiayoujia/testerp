<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesFirstLegs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firstleg_logisticses', function (Blueprint $table) {
            $table->dropColumn('formula');
            $table->dropColumn('cost');
            $table->integer('days')->comment('时效')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firstleg_logisticses', function (Blueprint $table) {
            $table->dropColumn('days');
        });
    }
}
