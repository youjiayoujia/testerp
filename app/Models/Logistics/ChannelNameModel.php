<?php
/**
 * 渠道物流名
 *
 * Created by PhpStorm.
 * User: mc
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ChannelNameModel extends BaseModel
{
    public $table = 'logistics_channel_names';

    public $fillable = [
        'channel_id', 'name', 'logistics_key'
    ];

    public $searchFields = ['name' => '名称'];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function logistics()
    {
        return $this->belongsToMany('App\Models\LogisticsModel', 'logistics_belongstos', 'logistics_channel_id', 'logistics_id');
    }

    public function logisticsCdiscount()
    {
        return $this->hasMany('App\Models\Sellmore\ShipmentModel', 'shipmentCdiscountCodeID', 'name');
    }

    public function logisticsAma()
    {
        return $this->hasMany('App\Models\Sellmore\ShipmentModel', 'shipmentAMZCode', 'name');
    }

    public function logisticsEbay()
    {
        return $this->hasMany('App\Models\Sellmore\ShipmentModel', 'shipmentCarrierInfo', 'name');
    }
}