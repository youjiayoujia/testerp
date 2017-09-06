<?php

namespace App\Models\Oversea;

use App\Base\BaseModel;

class ItemCostModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversea_item_costs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'code', 'cost', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=[];

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }
}
