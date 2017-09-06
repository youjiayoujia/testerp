<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:02
 */
namespace App\Models\Sellmore;

class EbayLogisticsModel extends SellMoreModel
{
    protected $table = 'erp_ebay_logistic';

    public function logisticses()
    {
        return $this->hasMany('App\Models\Sellmore\ShipmentModel', 'shipmentWishCodeID', 'logistics_id');
    }
}