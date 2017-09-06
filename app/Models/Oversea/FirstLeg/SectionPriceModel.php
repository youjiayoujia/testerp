<?php

namespace App\Models\Oversea\FirstLeg;

use App\Base\BaseModel;

class SectionPriceModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'firstLeg_sectionPrices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['weight_from', 'weight_to', 'cost', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=[];
}
