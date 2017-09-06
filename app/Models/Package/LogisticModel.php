<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class LogisticModel extends BaseModel
{
    public $table = 'package_logistics';

    protected $fillable = [
        'package_id',
        'logistic_code',
        'fee',
        'remark',
        'created_at'
    ];
}