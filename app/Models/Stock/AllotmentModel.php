<?php

namespace App\Models\Stock;

use App\Base\BaseModel;

class AllotmentModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stock_allotments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['allotment_id', 'out_warehouse_id', 'in_warehouse_id', 'remark', 'allotment_by', 'allotment_status', 'check_by', 'check_status', 'check_time', 'checkform_by', 'checkform_time', 'created_at'];

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [
                'check_status' => ['0' => '未审核', '1' => '未通过', '2' => '已通过'],
            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
            'doubleRelatedSearchFields' => [],
        ];
    }

    /**
     * search field 
     *
     *  @return
     */
    public $searchFields = ['allotment_id' => '调拨单号'];

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function allotmentByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'allotment_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function checkByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'check_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function checkformByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'checkform_by', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function outwarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'out_warehouse_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return 
     *
     */
    public function inwarehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'in_warehouse_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @return
     *
     */
    public function outposition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    /**
     * get the relationship between the two model 
     *
     *  @return
     *
     */
    public function allotmentforms()
    {
        return $this->hasMany('App\Models\Stock\AllotmentFormModel', 'stock_allotment_id', 'id');
    }

    /**
     * get the accessAttribute by the allotment_status
     *
     * @return
     */
    public function getStatusNameAttribute()
    {
        $buf = config('in.allotment');
        
        return $buf[$this->allotment_status];
    }

    /**
     * get the allotmentlogistics-$this model relationship
     * 
     * @return
     */
    public function logistics()
    {
        return $this->hasMany('App\Models\Stock\AllotmentLogisticsModel', 'allotment_id', 'id');
    }

    /**
     * 返回验证规则 
     *
     * @param $request request请求
     * @return $arr
     *
     */
    public function rule($request)
    {
        $arr = [
            'out_warehouse_id' => 'required|integer',
            'in_warehouse_id' => 'required|integer',
        ];
        $buf = $request->all();
        $buf = $buf['arr'];
        foreach($buf as $key => $val) 
        {
            if($key == 'sku')
                foreach($val as $k => $v)
                {
                    $arr['arr.sku.'.$k] ='required';
                }
            if($key == 'quantity')
                foreach($val as $k => $v)
                {
                    $arr['arr.quantity.'.$k] ='required|integer';
                }
            if($key == 'warehouse_positions_id')
                foreach($val as $k => $v)
                {
                    $arr['arr.warehouse_position_id.'.$k] = 'required|integer';
                }
            if($key == 'amount')
                foreach($val as $k => $v)
                {
                    $arr['arr.amount.'.$k] = 'required';
                }
        }

        return $arr;
    }

}
