<?php
/**
 * 中邮平邮客户信息
 *
 * Created by PhpStorm.
 * User: hejiancheng
 * Date: 16/10/12
 * Time: 12:55
 */

namespace App\Models;

use App\Base\BaseModel;

class PostpacketModel extends BaseModel
{
    protected $table = 'erp_postpacket_config';

    protected $fillable = [
        'id',
        'consumer_name',
        'consumer_from',
        'consumer_zip',
        'consumer_phone',
        'consumer_back',
        'sender_signature',
        'shipment_id_string',
        'consumer_remark',
    ];

}