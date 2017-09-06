<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:02
 */
namespace App\Models\Sellmore;

class LazadaLogisticsModel extends SellMoreModel
{
    protected $table = 'erp_lazada_logistics_service';

    public function logisticses()
    {
        return $this->hasMany('App\Models\Sellmore\ShipmentModel', 'shipmentLazadaCodeID', 'logistics_id');
    }
}