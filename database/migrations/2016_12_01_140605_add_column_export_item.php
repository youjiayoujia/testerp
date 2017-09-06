<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnExportItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_package_items', function (Blueprint $table) {
            $table->string('defaultName')->comment('默认名')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('export_package_items', function (Blueprint $table) {
            $table->dropColumn('defaultName');
        });
    }
}
