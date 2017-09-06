<?php
/*
 *Time:2016-10-15
 * joom标记发货上传追踪号记录表
 * user:hejiancheng
 */
namespace App\Models\Publish\Joom;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
class JoomShippingModel extends BaseModel
{
    protected $table = 'joom_shipping';

    protected $fillable = [
        'id',
        'account',
        'orderID',
        'joomID',
        'tracking_no',
        'requestTime',
        'erp_orders_status'
    ];
}