<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewTypeToRemark extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_remarks', function (Blueprint $table) {
            $table->enum('type', ['REQUIRE', 'PROFIT', 'MESSAGE', 'BLACK', 'WEIGHT', 'ITEM', 'DEFAULT'])
                ->comment('审核类型')->default('DEFAULT')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_remarks', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
