<?php
namespace App\Models;

use App\Base\BaseModel;

class WarehouseModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'warehouses';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'province',
        'city',
        'address',
        'contact_by',
        'telephone',
        'type',
        'volumn',
        'code',
        'is_available'
    ];

    // 规则验证
    public $rules = [
        'create' => [
            'name' => 'required|max:128|unique:warehouses,name',
            'type' => 'required',
        ],
        'update' => [
            'name' => 'required|max:128|unique:warehouses,name,{id}',
            'type' => 'required',
        ]
    ];

    //查询
    public $searchFields = ['name' => '仓库名'];

    /**
     * get the relationship
     *
     * @return
     *
     */
    public function positions()
    {
        return $this->hasMany('App\Models\Warehouse\PositionModel', 'warehouse_id', 'id');
    }

    //获取仓库地址
    public function getWarehouseAddressAttribute()
    {
        return $this->province . $this->city . $this->address;
    }

    /**
     * get the relationship
     *
     * @return
     *
     */
    public function contactByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'contact_by', 'id');
    }

    public function logistics()
    {
        return $this->hasMany('App\Models\LogisticsModel', 'warehouse_id', 'id');
    }

    public function logisticsRules()
    {
        return $this->hasManyThrough('App\Models\Logistics\RuleModel', 'App\Models\LogisticsModel',
            'warehouse_id', 'type_id');
    }

    public function overseaItemCost()
    {
        return $this->hasMany('App\Models\Oversea\ItemCostModel', 'code', 'code');
    }

    public function logisticsIn($id)
    {
        $logisticses = $this->logistics;
        foreach ($logisticses as $logistics) {
            if ($logistics->id == $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * 本地仓库id
     * @return array
     */
    public function getLocalIds(){
        $data = $this->select('id')->where('type', 'local')->get();
        if(! $data->isEmpty()){
            $data = $data->toArray();
        }else{
            $data = [];
        }
        return $data;
    }
}
