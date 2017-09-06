<?php

namespace App\Models;

use App\Base\BaseModel;

class CountriesSortModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries_sorts';

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
    public $searchFields=['name' => '地区名'];

    public function countries()
    {
        return $this->hasMany('App\Models\CountriesModel', 'parent_id', 'id');
    }
}
