<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtPriceTaskMain extends BaseModel
{
     protected $table = "smt_price_task_main";
     
     protected $fillable = ['token_id','shipment_id','shipment_id_op','percentage','re_pirce','status','group'];
}
