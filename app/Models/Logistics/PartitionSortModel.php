<?php
/**
 * 物流分区分类
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/10/23
 * Time: 下午11:11
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class PartitionSortModel extends BaseModel
{
    protected $table = 'logistics_partition_sorts';

    protected $fillable = ['logistics_partition_id', 'country_id'];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public $searchFields=[];

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'country_id', 'id');
    }

}