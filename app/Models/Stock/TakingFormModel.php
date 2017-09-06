<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class TakingFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stock_taking_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock_taking_id', 'stock_id', 'quantity', 'stock_taking_status', 'stock_taking_yn', 'created_at'];

    // 用于查询
    public $searchFields = [''];

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTaking()
    {
        return $this->belongsTo('App\Models\Stock\TakingModel', 'stock_taking_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function checkByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTakingByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'stock_taking_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function stockTakingAdjustment()
    {
        return $this->hasOne('App\Models\Stock\TakingAdjustmentModel', 'stock_taking_id', 'id');
    }
}
