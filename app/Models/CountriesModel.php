<?php

namespace App\Models;

use App\Base\BaseModel;

class CountriesModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'cn_name', 'code', 'number', 'parent_id', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    //查询
    public $searchFields=['cn_name' => '中文名', 'code' => '简码'];

    public function countriesSort()
    {
        return $this->belongsTo('App\Models\CountriesSortModel', 'parent_id', 'id');
    }
    
    public function shsHkZone(){
        return $this->belongsTo('App\Models\Logistics\Zone\SHSHKZoneModel', 'cn_name', 'country_cn');
    }
    
    public function geKou(){
        return $this->belongsTo('App\Models\Logistics\Zone\GeKouModel', 'cn_name', 'country');
    }
}
