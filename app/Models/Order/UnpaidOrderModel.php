<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/9/27
 * Time: 上午10:09
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class UnpaidOrderModel extends BaseModel
{
    public $table = 'order_unpaids';

    public $searchFields = ['ordernum' => '订单号'];

    public $fillable = [
        'id',
        'ordernum',
        'remark',
        'date',
        'channel_id',
        'customer_id',
        'note',
        'status',
    ];

    public $rules = [
        'create' => [
            'ordernum' => 'required',
            'remark' => 'required',
            'date' => 'required',
            'channel_id' => 'required',
            'customer_id' => 'required',
            'status' => 'required',
        ],
        'update' => [
            'ordernum' => 'required',
            'remark' => 'required',
            'date' => 'required',
            'channel_id' => 'required',
            'customer_id' => 'required',
            'status' => 'required',
        ],
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_id', 'id');
    }

    public function getStatusNameAttribute()
    {
        $config = config('order.unpaid_status');
        return $config[$this->status];
    }
}