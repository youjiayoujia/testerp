<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sync_api', function (Blueprint $table) {
            //
            $table->string('error_msg')->nullable()->comment('错误原因');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sync_api', function (Blueprint $table) {
            //
            $table->dropColumn('error_msg');
        });
    }
}
