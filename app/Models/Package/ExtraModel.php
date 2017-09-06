<?php
/**
 * 数据导出extra字段
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class ExtraModel extends BaseModel
{
    public $table = 'export_package_extras';

    protected $fillable = [
        'parent_id',
        'fieldName',
        'fieldValue',
        'fieldLevel'
    ];
}