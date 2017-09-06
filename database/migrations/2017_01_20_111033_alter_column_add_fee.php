<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnAddFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oversead_allotments', function (Blueprint $table) {
            $table->decimal('fee', 6,3)->comment('物流费')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oversead_allotments', function (Blueprint $table) {
            $table->dropColumn('fee');
        });
    }
}
