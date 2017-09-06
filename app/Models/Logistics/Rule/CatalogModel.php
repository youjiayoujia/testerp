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

class CatalogModel extends BaseModel
{
    protected $table = 'logistics_rule_catalogs';

    protected $fillable = [
        'logistics_rule_id','catalog_id','created_at'
    ];

    public $searchFields = [''];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}