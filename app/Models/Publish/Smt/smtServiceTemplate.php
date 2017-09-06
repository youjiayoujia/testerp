<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtServiceTemplate extends BaseModel
{
    protected $table = "smt_service_template";
    protected $fillable = ['id','token_id','serviceID','serviceName','last_update_time'];
    
    public function getServiceTemplateList($token_id){
       $rs = array();
       $result = $this->where('token_id',$token_id)->get();
        if ($result) {
            foreach ($result as $row) {
                $rs[$row['serviceID']] = $row;
            }
        }
        return $rs;
    }
}
