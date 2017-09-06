<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFourpxCountryCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fourpx_country_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_name')->comment('国家中文名');
            $table->string('code')->comment('国家简码');
            $table->string('partition')->comment('4PX分区');
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
        Schema::drop('fourpx_country_code');
    }
}
