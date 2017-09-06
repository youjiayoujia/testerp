<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportSyncApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_sync_api', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('relations_id')->nullable()->comment('需要同步记录的主键');
            $table->string('type')->nullable()->comment('数据类型，例如：product,supplier')->nullable();
            $table->string('route')->nullable()->comment('v3系统的路由')->nullable();
            $table->text('data')->nullable()->comment('序列化记录参数')->nullable();
            $table->enum('status', array('0','1'))->default('0')->comment('0：未同步；1：已同步');
            $table->integer('times')->nullable()->comment('尝试次数统计');
            $table->string('error_msg')->nullable()->comment('错误原因');
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
        Schema::drop('import_sync_api');
    }
}
