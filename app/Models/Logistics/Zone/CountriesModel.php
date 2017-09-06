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

class CountriesModel extends BaseModel
{
    protected $table = 'logistics_zone_countries';

    protected $fillable = [
        'logistics_zone_id', 
        'country_id'
    ];

    public $searchFields = [];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'shipping_country', 'code');
    }
}