<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlibabaSuppliersAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alibaba_suppliers_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('resource_owner')->comment('账户名');
            $table->string('memberId')->comment('买家id');
            //$table->string('aliId');
            //$table->string('refresh_token');
            $table->integer('purchase_user_id')->nullable();
            $table->string('access_token');
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
        Schema::drop('alibaba_suppliers_account');
    }
}
