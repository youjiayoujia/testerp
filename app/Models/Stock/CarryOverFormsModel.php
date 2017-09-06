<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class CarryOverFormsModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'carry_over_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'stock_id', 'purchase_price', 'begin_quantity', 'begin_amount', 'over_quantity', 'over_amount', 'created_at'];


    //查询
    public $searchFields = ['parent_id'];

    public function carryOver()
    {
        return $this->belongsTo('App\Models\Stock\CarryOverModel', 'parent_id', 'id');
    }

    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
    }
}
