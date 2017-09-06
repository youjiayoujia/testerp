<?php
/**
 * 产品模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/23
 * Time: 下午5:31
 */

namespace App\Models\Order;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{
    protected $table = 'order_items';

    protected $guarded = ['orderItemId'];

    public $searchFields = [
        'order_id',
        'item_id',
        'channel_id',
        'sku',
        'status',
        'item_status',
        'ship_status',
        'is_refund',
        'is_oversea',
        'code',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function ebayFeedback()
    {
        return $this->hasOne('App\Models\Publish\Ebay\EbayFeedBackModel', 'transaction_id', 'transaction_id');
    }

    public function getStatusNameAttribute()
    {
        $arr = config('order.item_status');
        return $arr[$this->status];
    }

    public function getIsActiveNameAttribute()
    {
        $arr = config('order.is_active');
        return $arr[$this->is_active];
    }

    public function getIsGiftNameAttribute()
    {
        $arr = config('order.whether');
        return $arr[$this->is_gift];
    }

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel','order_id');
    }

    public function getStatusTextAttribute()
    {
        return config('order.item_status.' . $this->status);
    }

    public function getItemChineseNameAttribute(){
        if(!empty($this->item)){
            return $this->item->c_name;
        }else{
            return '';
        }
    }

}