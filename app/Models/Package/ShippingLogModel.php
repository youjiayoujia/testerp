<?php
/** 标记发货日志模型
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-11-24
 * Time: 17:29
 */
namespace App\Models\Package;

use App\Base\BaseModel;
class ShippingLogModel extends BaseModel
{
    public $table = 'erp_shipping_log';

    public $searchFields = [];

    protected $fillable = [
        'package_id',
        'tracking_no',
        'logistics_channel_name',
        'shipping_time'
    ];
}