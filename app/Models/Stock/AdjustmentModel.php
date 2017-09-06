<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AdjustmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stock_adjustments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['adjust_form_id', 'warehouse_id', 'adjust_by', 'remark', 'status', 'check_by', 'check_time', 'created_at'];

    // 用于查询
    public $searchFields = ['adjust_form_id' => '调整单号'];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'status' => ['0' => '未审核', '1' => '未通过', '2' => '已通过'],
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
            'doubleRelatedSearchFields' => [],
        ];
    }

    /**
     * get the relationship between the two module 
     *
     * @return 
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }
    
    /**
     * return the relationship between the two Model 
     *
     * @return
     */
    public function adjustments()
    {
        return $this->hasMany('App\Models\Stock\AdjustFormModel', 'stock_adjustment_id', 'id');
    }

    /**
     * return the relationship between the two Model 
     *
     * @return
     */
    public function checkByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }

    /**
     * return the relationship between the two Model 
     *
     * @return
     */
    public function adjustByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'adjust_by', 'id');
    }

    /**
     * 返回验证规则 
     *
     * @param $request
     * @return $arr
     */
    public function rule($request)
    {
        $arr = [];
        $buf = $request->all();
        $buf = $buf['arr'];
        foreach($buf as $key => $val) 
        {
            if($key == 'sku')
                foreach($val as $k => $v)
                {
                    $arr['arr.sku.'.$k] ='required';
                }
            if($key == 'amount')
                foreach($val as $k => $v)
                {
                    $arr['arr.unit_cost.'.$k] ='required|numeric';
                }
            if($key == 'warehouse_position_id')
                foreach($val as $k => $v)
                {
                    $arr['arr.warehouse_position_id.'.$k] = 'required';
                }
        }

        return $arr;
    }
}
