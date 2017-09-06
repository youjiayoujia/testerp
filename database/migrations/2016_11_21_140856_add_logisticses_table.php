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
            $table->enum('is_confirm', ['0', '1'])->comment('是否确认')->default('0');
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
            $table->dropColumn('is_confirm');
        });
    }
}
