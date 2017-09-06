<?php
/**
 * 渠道物流名
 *
 * Created by PhpStorm.
 * User: mc
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class BelongsToModel extends BaseModel
{
    protected $table = 'logistics_belongstos';

    protected $fillable = [
        'logistics_id', 'logistics_channel_id'
    ];

    public $searchFields = [];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}