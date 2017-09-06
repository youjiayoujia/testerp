<?php

namespace App\Models\Oversea\Box;

use App\Base\BaseModel;

class BoxFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversead_box_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'sku', 'quantity', 'created_at'];

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
        return $this->belongsTo('App\Models\ItemModel', 'sku', 'sku');
    }
}
