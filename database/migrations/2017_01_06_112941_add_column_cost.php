<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('firstleg_logisticses', function (Blueprint $table) {
            $table->decimal('cost', 6,3)->comment('单价')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firstleg_logisticses', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
}
