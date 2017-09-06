<?php
/**
 * 物流分区模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/1/6
 * Time: 上午11:22
 */

namespace App\Models\Logistics\Zone;

use App\Base\BaseModel;

class SectionPriceModel extends BaseModel
{
    protected $table = 'logistics_zone_section_prices';

    protected $fillable = [
        'logistics_zone_id',
        'weight_from',
        'weight_to',
        'price'
    ];

    public $searchFields = [];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}