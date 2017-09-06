<?php

namespace App\Models;

use App\Base\BaseModel;

class CurrencyModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'currencys';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'identify', 'rate', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['code' => '货币简称'];

    public function getRate($code)
    {
        if($this->where('code', trim($code))->first()) {
            return $this->where('code', trim($code))->first()->rate;
        }
        
        return false;
    }

    public function getUsdToParamRate($param){
        if(!empty($param)){
            $currency =  $this->where('code',$param)->first();
            return (1/$currency->rate);
        }
    }
}
