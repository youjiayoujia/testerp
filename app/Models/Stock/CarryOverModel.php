<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class CarryOverModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stock_carry_overs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['carry_over_time', 'date', 'warehouse_id'];


    //查询
    public $searchFields = ['date' => '月结时间'];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    public function forms()
    {
        return $this->hasMany('App\Models\Stock\CarryOverFormsModel', 'parent_id', 'id');
    }
}
