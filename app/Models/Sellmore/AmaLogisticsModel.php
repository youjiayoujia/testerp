<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:02
 */
namespace App\Models\Sellmore;

class AmaLogisticsModel extends SellMoreModel
{
    protected $table = 'erp_amz_logistic';

    public function logistics()
    {
        return $this->hasOne('App\Models\Sellmore\ShipmentModel', '');
    }
}