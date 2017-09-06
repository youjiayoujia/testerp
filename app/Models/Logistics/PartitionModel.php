<?php
/**
 * 物流分区模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/10/23
 * Time: 上午12:33
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class PartitionModel extends BaseModel
{
    public $table = 'logistics_partitions';

    public $fillable = ['name'];

    public $rules = [
        'create' => [
            'name' => 'required',
        ],
        'update' => [
            'name' => 'required',
        ]
    ];

    public $searchFields=['name' => '物流分区名'];

    public function partitionSorts()
    {
        return $this->hasMany('App\Models\Logistics\PartitionSortModel', 'logistics_partition_id', 'id');
    }

    public function countries()
    {
        return $this->hasMany('App\Models\CountriesModel', 'id', 'country_id');
    }
}