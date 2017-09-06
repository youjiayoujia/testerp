<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToOrderMarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_mark_logic', function (Blueprint $table) {
            $table->string('name')->comment('规则名称')->default('');
            $table->string('expired_time')->comment('最晚天数')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('order_mark_logic', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('expired_time');
        });
    }
}
