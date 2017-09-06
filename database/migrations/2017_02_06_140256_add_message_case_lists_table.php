<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageCaseListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebay_cases_lists', function (Blueprint $table) {
            //
            $table->enum('process_status', array('UNREAD','PROCESS','COMPLETE'))->default('UNREAD')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebay_cases_lists', function (Blueprint $table) {
            //
            $table->dropColumn('process_status');
        });
    }
}
