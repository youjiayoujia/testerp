<?php
/**
 * 跟踪号模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class LimitsModel extends BaseModel
{
    protected $table = 'logistics_limits';

    protected $fillable = [
        'name',
        'ico'
    ];

    public $searchFields = ['name' => '物流限制名称'];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}