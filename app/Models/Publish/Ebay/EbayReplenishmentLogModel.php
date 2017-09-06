<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-11-07
 * Time: 14:24
 */

namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;

class EbayReplenishmentLogModel extends BaseModel
{

    protected $table = 'ebay_replenishment_log';

    protected $fillable = [
        'token_id',
        'order_id',
        'item_id',
        'sku',
        'quantity',
        'remark',
        'is_mul',
        'is_api_success',
        'update_time'
    ];

    public $searchFields = [];

    protected $rules = [
    ];
}