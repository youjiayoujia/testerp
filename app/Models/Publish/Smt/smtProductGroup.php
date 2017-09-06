<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;

class smtProductGroup extends BaseModel
{
    protected $table ="smt_product_group";
    
    protected $fillable = ['token_id','group_id','group_name','parent_id','last_update_time'];
    
    public $searchFields = ['group_id'=>'产品组ID'];
    /**
     * 获取账号的产品分组列表并组装成原获取的数据格式
     * @param  [type] $token_id 账号ID
     * @return [type]           [description]
     */
    public function getProductGroupList($token_id){
       $result = array();
       $rs = array();
       if($token_id){
           $result = $this->where('token_id',$token_id)->get();
       }else{
           $result = $this->all();
       }
        if ($result) {
            $i = 0;
            $str = '';
            foreach ($result as $row) {
                $row = $row->toArray();
                if ($row['parent_id'] == '0') { //说明是一级产品分组
                    if($str == $row['group_id']){
                        $rs[$row['group_id'] + $i] = $row;
                    }else{
                        $rs[$row['group_id']] = $row;
                    }
                    $str = $row['group_id'];
                    $i++;
                }else {
                    $rs[$row['parent_id']]['child'][] = $row;
                }
            }
        }
       
        return $rs;
    }
}
