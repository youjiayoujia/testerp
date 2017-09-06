<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailPushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_push', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('code')->default('NULL')->comment('变量代码');
            $table->string('name')->default('NULL')->comment('变量名');
            $table->string('description')->default('NULL')->comment('变量描述');
            $table->string('value')->default('NULL')->comment('变量值');
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
        Schema::drop('mail_push', function (Blueprint $table) {
            //
        });
    }
}
