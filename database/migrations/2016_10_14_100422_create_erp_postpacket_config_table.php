<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpPostpacketConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_postpacket_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('consumer_name')->comment('协议客户');
            $table->text('consumer_from')->comment('发件地址');
            $table->string('consumer_zip')->comment('邮编');
            $table->string('consumer_phone')->comment('电话');
            $table->string('consumer_back')->comment('退件单位');
            $table->string('sender_signature')->comment('寄件人签名');
            $table->text('shipment_id_string')->comment('关联的物流ID');
            $table->text('consumer_remark')->comment('备注');
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
        Schema::drop('erp_postpacket_config');
    }
}
