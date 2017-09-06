<?php

namespace App\Models\Oversea\Stock;

use App\Base\BaseModel;

class AdjustmentFormModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversea_stock_adjustment_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'sku', 'oversea_sku', 'oversea_cost', 'warehouse_position', 'type', 'quantity', 'remark', 'created_at'];

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
