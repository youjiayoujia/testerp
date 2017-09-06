<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_channel', function (Blueprint $table) {
            $table->enum('delivery', ['0', '1'])->comment('缺货是否标记发货')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_channel', function (Blueprint $table) {
            $table->dropColumn('delivery');
        });
    }
}
