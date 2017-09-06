<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnBoxesOversead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oversead_boxes', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->dropColumn('height');
            $table->dropColumn('width');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oversead_boxes', function (Blueprint $table) {
            //
        });
    }
}
