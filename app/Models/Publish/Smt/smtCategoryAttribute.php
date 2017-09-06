<?php

namespace App\models\Publish\Smt;

use Illuminate\Database\Eloquent\Model;

class smtCategoryAttribute extends Model
{
    protected $table = "smt_category_attribute";
    protected $fillable = ['category_id','attribute','last_update_time'];
    
    
}
