<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AllotmentFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'allotment_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_allotment_id', 'warehouse_position_id', 'item_id', 'quantity', 'amount', 'receive_quantity', 'in_warehouse_position_id', 'created_at'];

    /**
     * return the relationship between the two Model 
     *
     * @return
     *
     */
    public function allotment()
    {
        return $this->belongsTo('App\Models\Stock\AllotmentModel', 'stock_allotment_id', 'id');
    }

    /**
     * return the relationship between the two Model 
     *
     * @return
     *
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    /**
     * return the relationship between the two Model
     *
     * @return 
     */
    public function inposition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'in_warehouse_position_id', 'id');
    }

    /**
     * return the relationship between the two Model
     *
     * @return 
     */
    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }
}
