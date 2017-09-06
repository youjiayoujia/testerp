<?php
/**
 * 订单需求模型
 *
 * 2016-04-19
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models;

use App\Base\BaseModel;

class RequireModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requires';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $searchFields = ['sku'];

    protected $rules = [];

    public function order()
    {
        return $this->belongsTo('App\Models\OrderModel', 'order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo('App\Models\Order\ItemModel', 'order_item_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }
}
