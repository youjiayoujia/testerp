<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('default_keywords')->comment('默认关键词')->default(NULL);
            $table->string('default_name')->comment('标题')->default(NULL);
            $table->string('recieve_wrap_id')->comment('收货包装id')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('default_keywords');
            $table->dropColumn('default_name');
            $table->dropColumn('recieve_wrap_id');
        });
    }
}
