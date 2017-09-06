<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oversead_boxes', function (Blueprint $table) {
            $table->timestamp('shipped_at')->comment('发货时间')->default('0000-00-00');
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
            $table->dropColumn('shipped_at');
        });
    }
}
