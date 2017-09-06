<?php
/**
 * Created by Norton.
 * User: Administrator
 * Date: 2016/6/22
 * Time: 10:59
 */
namespace App\Models\Message;

use App\Base\BaseModel;
class OrderModel extends BaseModel{

    protected $table = 'message_orders';
    protected $fillable = [
        'message_id',
        'order_id',
    ];

    public function message()
    {
        return $this->belongsTo('App\Models\MessageModel', 'message_id');
    }

    public function order()
    {
        return $this->hasOne('App\Models\OrderModel', 'id', 'order_id');
    }

}