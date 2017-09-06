<?php
/**
 * 回邮模版模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/11
 * Time: 下午3:54
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class EmailTemplateModel extends BaseModel
{
    public $table = 'logistics_email_templates';

    public $searchFields = [
        'customer' => '协议客户',
        'zipcode' => '邮编',
        'phone' => '电话',
        'unit' => '退件单位',
        'sender' => '寄件人'
    ];

    public $fillable = [
        'name',
        'customer',
        'address',
        'zipcode',
        'phone',
        'unit',
        'sender',
        'remark',
        'country_code',
        'province',
        'city',
        'type',
        
        'eub_api',
        'eub_head',
        'eub_weather',
        'eub_print_type',
        'eub_transport_type',
        'eub_contact_company_name',
        'eub_contact_name',
        'eub_street',
        'eub_zone_code',
        'eub_city_code',
        'eub_province_code',
        'eub_zipcode',
        'eub_country',
        'eub_email',
        'eub_mobile_phone',
        'eub_phone',
        'eub_sender',
        'eub_sender_company',
        'eub_sender_street',
        'eub_sender_zone',
        'eub_sender_city',
        'eub_sender_province',
        'eub_sender_province_code',
        'eub_sender_city_code',
        'eub_sender_zone_code',
        'eub_sender_country',
        'eub_sender_zipcode',
        'eub_sender_email',
        'eub_sender_mobile_phone',
        'eub_default_value',
        'eub_default_weight',
        'eub_default_cn_name',
        'eub_default_name',
        'eub_default_code',
        'eub_return_contact',
        'eub_return_company',
        'eub_return_address',
        'eub_return_zone',
        'eub_return_city',
        'eub_return_province',
        'eub_return_country',
        'eub_return_zipcode'
    ];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];

}