<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShunyouAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shunyou_area', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_cn')->comment('中文名');
            $table->string('country_code')->comment('code');
            $table->string('area_code')->comment('数字分区');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shunyou_area');
    }
}
