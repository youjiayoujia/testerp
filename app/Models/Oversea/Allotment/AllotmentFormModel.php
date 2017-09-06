<?php

namespace App\Models\Oversea\Allotment;

use App\Base\BaseModel;

class AllotmentFormModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversead_allotment_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'warehouse_position_id', 'quantity', 'inboxed_quantity', 'parent_id'];

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

    public function allotment()
    {
        return $this->belongsTo('App\Models\Oversea\Allotment\AllotmentModel', 'parent_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }
}
