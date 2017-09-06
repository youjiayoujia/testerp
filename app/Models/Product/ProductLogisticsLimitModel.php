<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/9/24
 * Time: 下午4:54
 */

namespace App\Models\Product;

use App\Base\BaseModel;

class ProductLogisticsLimitModel extends BaseModel
{
    protected $table = 'product_logistics_limits';

    protected $fillable = [
        'product_id','logistics_limits_id'
    ];

    public $searchFields = [''];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}