<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEmailLogistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_email_templates', function (Blueprint $table) {
            $table->enum('type', ['default', 'eub'])->comment('类型')->default('default');
            $table->string('eub_api')->comment('api信息')->default(NULL);
            $table->string('eub_head')->comment('回邮地址标题')->default(NULL);
            $table->enum('eub_weather', ['0','1'])->comment('是否使用E邮宝API')->default('1');
            $table->enum('eub_print_type', ['0', '1'])->comment('EUB打印方式')->default('0');
            $table->enum('eub_transport_type', ['0','1'])->commennt('U邮宝交运方式')->default('0');
            $table->string('eub_contact_name')->comment('联系人')->default(NULL);
            $table->string('eub_contact_company_name')->comment('公司名称')->default(NULL);
            $table->string('eub_street')->comment('街道')->default(NULL);
            $table->string('eub_zone_code')->comment('地区代码')->default(NULL);
            $table->string('eub_city_code')->comment('城市代码')->default(NULL);
            $table->string('eub_province_code')->comment('省份代码')->default(NULL);
            $table->string('eub_zipcode')->comment('邮编')->default(NULL);
            $table->string('eub_country')->comment('国家')->default(NULL);
            $table->string('eub_email')->comment('邮编')->default(NULL);
            $table->string('eub_mobile_phone')->comment('移动电话')->default(NULL);
            $table->string('eub_phone')->comment('固定电话')->default(NULL);
            $table->string('eub_sender')->comment('寄件人')->default(NULL);
            $table->string('eub_sender_company')->comment('寄件人公司')->default(NULL);
            $table->string('eub_sender_street')->comment('寄件人街道')->default(NULL);
            $table->string('eub_sender_zone')->commenet('寄件人地区')->default(NULL);
            $table->string('eub_sender_city')->comment('寄件人城市')->default(NULL);
            $table->string('eub_sender_province')->comment('寄件人省')->default(NULL);
            $table->string('eub_sender_province_code')->comment('寄件人省编码')->default(NULL);
            $table->string('eub_sender_city_code')->comment('寄件人城市编码')->default(NULL);
            $table->string('eub_sender_zone_code')->comment('寄件人地区编码')->default(NULL);
            $table->string('eub_sender_country')->comment('寄件人国家')->default(NULL);
            $table->string('eub_sender_zipcode')->comment('寄件人邮编')->default(NULL);
            $table->string('eub_sender_mobile_phone')->comment('寄件人移动电话')->default(NULL);
            $table->string('eub_sender_email')->comment('寄件人邮箱')->default(NULL);
            $table->decimal('eub_default_value', 6,2)->comment('默认申报价值')->default(0);
            $table->decimal('eub_default_weight',6,2)->comment('默认申报重量')->default(0);
            $table->string('eub_default_cn_name')->comment('默认申报中文名')->default(NULL);
            $table->string('eub_default_name')->comment('默认申报英文名')->default(NULL);
            $table->string('eub_default_code')->comment('默认国家或地区代码')->default(NULL);
            $table->string('eub_return_contact')->comment('返回包裹-联系人')->default(NULL);
            $table->string('eub_return_company')->comment('返回包裹--公司名')->default(NULL);
            $table->string('eub_return_address')->comment('返回地址')->default(NULL);
            $table->string('eub_return_zone')->comment('返回地区')->default(NULL);
            $table->string('eub_return_city')->comment('返回城市')->default(NULL);
            $table->string('eub_return_province')->comment('返回省')->default(NULL);
            $table->string('eub_return_country')->comment('返回国家')->default(NULL);
            $table->string('eub_return_zipcode')->comment('返回邮编')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_email_templates', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('eub_api');
            $table->dropColumn('eub_head');
            $table->dropColumn('eub_weather');
            $table->dropColumn('eub_print_type');
            $table->dropColumn('eub_transport_type');
            $table->dropColumn('eub_contact_company_name');
            $table->dropColumn('eub_contact_name');
            $table->dropColumn('eub_street');
            $table->dropColumn('eub_zone_code');
            $table->dropColumn('eub_city_code');
            $table->dropColumn('eub_province_code');
            $table->dropColumn('eub_zipcode');
            $table->dropColumn('eub_country');
            $table->dropColumn('eub_email');
            $table->dropColumn('eub_mobile_phone');
            $table->dropColumn('eub_phone');
            $table->dropColumn('eub_sender');
            $table->dropColumn('eub_sender_company');
            $table->dropColumn('eub_sender_street');
            $table->dropColumn('eub_sender_zone');
            $table->dropColumn('eub_sender_city');
            $table->dropColumn('eub_sender_province');
            $table->dropColumn('eub_sender_province_code');
            $table->dropColumn('eub_sender_city_code');
            $table->dropColumn('eub_sender_zone_code');
            $table->dropColumn('eub_sender_country');
            $table->dropColumn('eub_sender_zipcode');
            $table->dropColumn('eub_sender_email');
            $table->dropColumn('eub_sender_mobile_phone');
            $table->dropColumn('eub_default_value');
            $table->dropColumn('eub_default_weight');
            $table->dropColumn('eub_default_cn_name');
            $table->dropColumn('eub_default_name');
            $table->dropColumn('eub_default_code');
            $table->dropColumn('eub_return_contact');
            $table->dropColumn('eub_return_company');
            $table->dropColumn('eub_return_address');
            $table->dropColumn('eub_return_zone');
            $table->dropColumn('eub_return_city');
            $table->dropColumn('eub_return_province');
            $table->dropColumn('eub_return_country');
            $table->dropColumn('eub_return_zipcode');
        });
    }
}
