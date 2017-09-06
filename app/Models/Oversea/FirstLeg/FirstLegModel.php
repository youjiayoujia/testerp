<?php

namespace App\Models\Oversea\FirstLeg;

use App\Base\BaseModel;

class FirstLegModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'firstLeg_logisticses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['warehouse_id', 'name', 'transport', 'created_at', 'days'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['name' => '物流名'];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    public function forms()
    {
        return $this->hasmany('App\Models\Oversea\FirstLeg\SectionPriceModel', 'parent_id', 'id');
    }
}
