<?php
/**
 * 订单备注模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/5/24
 * Time: 下午3:05
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class RemarkModel extends BaseModel
{
    protected $table = 'order_remarks';

    public $searchFields = ['order_id', 'remark',];

    protected $fillable = [
        'order_id',
        'user_id',
        'remark',
        'type',
    ];

    public $rules = [
        'create' => [
            'remark' => 'required',
        ],
        'update' => [
            'remark' => 'required',
        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }

}