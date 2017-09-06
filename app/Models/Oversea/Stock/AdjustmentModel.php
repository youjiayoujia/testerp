<?php

namespace App\Models\Oversea\Stock;

use App\Base\BaseModel;

class AdjustmentModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'oversea_stock_adjustments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'adjust_by', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=[];

    public function adjustBy()
    {
        return $this->belongsTo('App\Models\UserModel', 'adjust_by', 'id');
    }

    public function forms()
    {
        return $this->hasMany('App\Models\Oversea\Stock\AdjustmentFormModel', 'parent_id', 'id');
    }
}
