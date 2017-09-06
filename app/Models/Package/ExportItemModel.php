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

class ExportItemModel extends BaseModel
{
    public $table = 'export_package_items';

    protected $fillable = [
    	'parent_id',
        'defaultName',
        'name',
        'level'
    ];
}