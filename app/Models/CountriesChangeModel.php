<?php
/**
 * 国家转换模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/11/25
 * Time: 下午1:41
 */

namespace App\Models;

use App\Base\BaseModel;

class CountriesChangeModel extends BaseModel
{
    protected $table = 'countries_change';

    protected $fillable = [
        'country_from', 'country_to'
    ];

    public $searchFields = ['country_from' => '来源国家', 'country_to' => '目标国家'];

    public $rules = [
        'create' => [
            'country_from' => 'required',
            'country_to' => 'required',
        ],
        'update' => [
            'country_from' => 'required',
            'country_to' => 'required',
        ],
    ];
}