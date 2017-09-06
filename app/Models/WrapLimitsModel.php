<?php

namespace App\Models;

use App\Base\BaseModel;

class wrapLimitsModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wrap_limits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['name' => '包装限制名称'];

    public function getRate($code)
    {
        if($this->where('code', trim($code))->first()) {
            return $this->where('code', trim($code))->first()->rate;
        }
        
        return false;
    }
}
