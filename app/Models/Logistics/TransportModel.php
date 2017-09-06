<?php
/**
 * 渠道展示编码模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/30
 * Time: 下午2:40
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class TransportModel extends BaseModel
{
    public $table = 'logistics_transports';

    public $searchFields = ['name' => '名称'];

    public $fillable = [
        'name',
        'code',
    ];

    public $rules = [
        'create' => [
            'name' => 'required',
        ],
        'update' => [
            'name' => 'required',
        ],
    ];
}