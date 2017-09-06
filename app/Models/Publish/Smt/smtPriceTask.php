<?php

namespace App\Models\Publish\Smt;
use App\Base\BaseModel;

class smtPriceTask extends BaseModel
{
    protected $table = "smt_price_task";
    
    protected $fillable = [
        'productID',
        'account',
        'status',
        'shipment_id',
        'percentage',
        're_pirce',
        'main_id',
        'api_time',
        'remark'
    ];
}
