<?php
/**
 * 跟踪号模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics\Rule;

use App\Base\BaseModel;

class CountryModel extends BaseModel
{
    protected $table = 'logistics_rule_countries';

    protected $fillable = [
        'logistics_rule_id','country_id','created_at'
    ];

    public $searchFields = [''];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}