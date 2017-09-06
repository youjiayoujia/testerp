<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtFreightTemplate extends BaseModel
{
    protected $table = "smt_freight_template";
    protected $fillable = ['id','token_id','templateId','templateName','default','freightSettingList','last_update_time',];
    

}
