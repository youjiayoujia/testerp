<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AdjustFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'adjust_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_adjustment_id', 'item_id', 'type', 'warehouse_position_id', 'quantity', 'amount', 'created_at'];


    //查询
    public $searchFields = ['stock_adjustment_id'];
    
    /**
     * return the relationship between the two Model 
     *
     * @return 
     *
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }
}
