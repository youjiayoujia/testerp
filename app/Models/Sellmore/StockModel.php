<?php

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/6/24
 * Time: 上午11:02
 */
namespace App\Models\Sellmore;

class StockModel extends SellMoreModel
{
    protected $table = 'erp_stock_detail';

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'products_sku', 'sku');
    }
}