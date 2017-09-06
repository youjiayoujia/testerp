<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTokenTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_token_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('账号');
            $table->string('account_token')->comment('账号token');
            $table->string('type')->comment('账号类型(区分账号所属)');
            $table->string('grant')->comment('account_data');
            $table->string('remark')->comment('备注');
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
        Schema::drop('account_token_type');
    }
}
