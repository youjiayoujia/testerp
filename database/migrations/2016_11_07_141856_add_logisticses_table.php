<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logisticses', function (Blueprint $table) {
            $table->enum('is_express', array('0','1'))->comment('平邮or快递')->default('0')->before('is_enable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logisticses', function (Blueprint $table) {
            $table->dropColumn('is_express');
        });
    }
}
