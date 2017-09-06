<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtProductUnit extends BaseModel
{
    protected $table = "smt_product_unit";
    
    public function getAllUnit(){
        $rs = array();
        $result = $this->all()->toArray();    
        if ($result) {
            foreach ($result as $row) {
                $rs[$row['id']] = $row;
            }
        }
        return $rs;
       
    }
}
