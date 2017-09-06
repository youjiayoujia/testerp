<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPickReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pick_reports', function (Blueprint $table) {
            $table->integer('today_picklist_undone')->comment('今日已分配未完成拣货单')->default(0);
            $table->integer('more_than_twenty_four')->comment('超过24小时未完成')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pick_reports', function (Blueprint $table) {
            $table->dropColumn('today_picklist_undone');
            $table->dropColumn('more_than_twenty_four');
        });
    }
}
