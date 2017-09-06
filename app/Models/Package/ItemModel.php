<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ItemModel extends BaseModel
{
    public $table = 'package_items';

    protected $fillable = [
        'item_id',
        'warehouse_position_id',
        'package_id',
        'order_item_id',
        'quantity',
        'picked_quantity',
        'remark',
        'code',
        'is_oversea',
        'sku',
    ];

    public function package()
    {
        return $this->belongsTo('App\Models\PackageModel', 'package_id');
    }

    public function stockOut()
    {
        return $this->hasOne('App\Models\Stock\OutModel', 'relation_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }

    public function orderItem()
    {
        return $this->belongsTo('App\Models\Order\ItemModel', 'order_item_id');
    }

    public function warehousePosition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id');
    }

    public function getModelNameAttribute()
    {
        $item = $this->item;
        return $item->product ? $item->product->id : '';
    }
}