<?php
/**
 * 黑名单地址验证
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/12/26
 * Time: 下午2:54
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class BlacklistAddressModel extends BaseModel
{
    public $table = 'order_blacklist_address';

    public $searchFields = ['address' => '地址'];

    public $fillable = [
        'id',
        'address',
    ];

    public $rules = [
        'create' => [
            'address' => 'required',
        ],
        'update' => [
            'address' => 'required',
        ],
    ];

}