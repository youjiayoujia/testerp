<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtTemplates extends BaseModel
{
    protected $table = "smt_template";
    
    protected $fillable = ['id','plat','token_id','name','pic_path','content'];
}
