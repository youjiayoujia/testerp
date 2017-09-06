<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAliexpressIssuesListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aliexpress_issues_list', function (Blueprint $table) {
            //
            $table->integer('is_platform_process')->defaul(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aliexpress_issues_list', function (Blueprint $table) {
            //
        });
    }
}
