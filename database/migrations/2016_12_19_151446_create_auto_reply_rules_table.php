<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoReplyRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_reply_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', array('OFF','ON'))->default('OFF')->comment('状态');
            $table->integer('channel_id');
            $table->string('message_keywords')->nullable()->comment('消息中的关键词');
            $table->string('reply_keywords')->nullable()->comment('回复中的关键词');
            $table->string('label_keywords')->nullable()->comment('主题中的标签关键词');
            $table->dateTime('filter_start_time')->nullable()->comment('过滤开始时间');
            $table->dateTime('filter_end_time')->nullable()->comment('过滤结束时间');
            $table->string('name')->comment('规则名称');
            $table->integer('create_by')->comment('规则创建者');
            $table->text('template')->comment('信息模版');
            $table->enum('type_time_filter', array('OFF','ON'))->default('OFF')->comment('平邮收件时间区间');
            $table->enum('type_shipping_one_month', array('OFF','ON'))->default('OFF')->comment('smt平邮发货1个月');
            $table->enum('type_shipping_one_two_month', array('OFF','ON'))->default('OFF')->comment('smt平邮发货1-2个月');
            $table->enum('type_shipping_fifty_day', array('OFF','ON'))->default('OFF')->comment('wish发货五十天');
            $table->enum('type_within_tuotou', array('OFF','ON'))->default('OFF')->comment('wish平台妥投');
            $table->enum('type_ebay_address', array('OFF','ON'))->default('OFF')->comment('ebay客人要求更改地址的');
            $table->enum('type_ebay_color', array('OFF','ON'))->default('OFF')->comment('ebay客人要求选颜色的');
            $table->enum('type_ebay_twenty_five_day', array('OFF','ON'))->default('OFF')->comment('ebay距离发货时间距离大于等于25天');

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
        Schema::drop('auto_reply_rules');
    }
}
