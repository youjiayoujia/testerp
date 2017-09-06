<?php
/**
 * 物流分类模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/11
 * Time: 下午2:04
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class CatalogModel extends BaseModel
{
    public $table = 'logistics_catalogs';

    public $searchFields = ['name' => '物流分类名称'];

    public $fillable = [
        'id',
        'name',
    ];

    public $rules = [
        'create' => [
            'name' => 'required|unique:logistics_catalogs,name',
        ],
        'update' => [
            'name' => 'required|unique:logistics_catalogs,name,{id}',
        ],
    ];

    public function logisticses()
    {
        return $this->hasMany('App\Models\LogisticsModel', 'logistics_catalog_id', 'id');
    }

}