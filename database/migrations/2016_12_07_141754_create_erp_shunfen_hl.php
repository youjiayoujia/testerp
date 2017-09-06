<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpShunfenHl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_shunfen_hl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_en')->comment('国家英文名');
            $table->string('country_cn')->comment('国家中文名');
            $table->string('code')->comment('国家简码');
            $table->string('gh')->comment('挂号分拣码');
            $table->string('py')->comment('平邮分拣码');
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
        Schema::drop('erp_shunfen_hl');
    }
}
