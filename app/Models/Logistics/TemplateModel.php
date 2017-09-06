<?php
/**
 * 面单模版模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/13
 * Time: 下午2:42
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class TemplateModel extends BaseModel
{
    public $table = 'logistics_templates';

    public $searchFields = ['name' => '面单名称', 'view' => '视图'];

    public $fillable = [
        'id',
        'name',
        'view',
        'size',
        'is_confirm'
    ];

    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics_templates,name',
            'view' => 'required',
            'size' => 'required'
        ],
        'update' => [
            'name' => 'required|unique:logistics_templates,name,{id}',
            'view' => 'required',
            'size' => 'required'
        ],
    ];

}