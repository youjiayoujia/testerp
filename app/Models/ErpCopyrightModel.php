<?php

namespace App\Models;

use App\Base\BaseModel;

class ErpCopyrightModel extends BaseModel
{
    public $table = 'erp_copyright';    
    
    protected $fillable = [
        'plat',
        'account',
        'sku',
        'pro_id',
        'complainant',
        'reason',
        'trademark',
        'ip_number',
        'degree',
        'violatos_number',
        'violatos_big_type',
        'violatos_small_type',
        'status',
        'score',
        'violatos_start_time',
        'violatos_fail_time',
        'seller',
        'remarks',
        'import_time',
        'import_uid',
        'contact_name',
        'phone',
        'email',
        'is_del',       
    ];
    public $searchFields = ['sku'=>'SKU'];

    public function getMixedSearchAttribute()
    {
        return [
             'filterSelects' => [
                 'plat' => [              
                     '6' => 'SMT',
                     '13'=> 'wish',
                     '1' => 'ebay',
                     ],
                  'account' => []  
              ],
             'filterFields' => ['pro_id','complainant','trademark','violatos_number'],
             'sectionSelect' => ['time' => ['import_time']],
        ];
    }
    
    public function user(){
        return $this->belongsTo('App\Models\UserModel', 'import_uid');
    }
    
    /**
     * 批量导入侵权数据
     * @param array $csvArr
     */
    public function excelInsertCoprightData($csvArr){
        try{
            $insertData = array();
            $plat = 0;
            $status =0 ;
            
            $copyrightList = $this->get()->toArray();
            foreach($csvArr as $key => $value){                
                 foreach($copyrightList as $copyright){                 
                     if(trim($value[2]) == 'smt' || trim($value[2]) == 'ebay'){  //重复性检查
                         if(trim($value[1]) == 'M13'){
                             if($copyright['account'] == 'M13' && ($copyright['trademark'] == trim($value[7]) && trim($value[7]) != 'null')){
                                 continue 2;
                             }                         
                         }else{
                             if($copyright['account'] != 'M13' && ($copyright['pro_id'] == trim($value[4]) && trim($value[4]) != 'null')) {
                                 continue 2;
                             }
                         }
                     }else{                    
                         if ($copyright['account'] == trim($value[1]) && $copyright['trademark'] == trim($value[7])) {
                             continue 2;
                         } 
                     }
                 }
                 switch(trim($value[2])){
                     case 'ebay': $plat = 1; break;
                     case 'smt' : $plat = 6; break;
                     case 'wish': $plat = 13;break;
                 }
                  
                 if(!$plat){
                     continue;
                 }
                 if($value[16]=='有效'){
                     $status = 1;
                 }
                 $insertData['account'] = $value[1];
                 $insertData['plat'] = $plat;
                 $insertData['sku'] = $value[3];
                 $insertData['pro_id'] = $value[4];
                 $insertData['complainant'] = $value[5];
                 $insertData['reason'] = $value[6];
                 $insertData['trademark'] = $value[7];
                 $insertData['ip_number'] = $value[8];
                 $insertData['degree'] = $value[9];
                 $insertData['violatos_number'] = $value[10];
                 $insertData['violatos_big_type'] = $value[11];
                 $insertData['violatos_small_type'] = $value[12];
                 $insertData['score'] = $value[13];
                 $insertData['violatos_start_time'] = $value[14];
                 $insertData['violatos_fail_time'] = $value[15];
                 $insertData['status'] = $status;
                 $insertData['seller'] = $value[17];
                 $insertData['remarks'] = $value[18];
                 $insertData['contact_name'] = $value[19];
                 $insertData['phone'] = $value[20];
                 $insertData['email'] = $value[21];
                 $insertData['import_time'] = date('Y-m-d H:i:s');
                 $insertData['import_uid'] = request()->user()->id;
                 $insertData['is_del'] = 1;
                 $this->create($insertData);
                 unset($insertData);
            }
        }catch(Exception $e){
            return false; 
        }
        return true;
    }
    
    /**
     * 获取全部侵权数据
     * @return array
     */
    public function getAll(){
        $all = $this->all();
        $cellData = array();
        foreach ($all as $model){
            switch($model->plat){
                case '1' : $plat = 'ebay'; break;
                case '6' : $plat = 'smt' ; break;
                case '13': $plat = 'wish'; break;
            }
            
            $cellData[]  = [                
                '帐号' => $model->account,
                '平台' => $plat,
                'SKU' => $model->sku,
                '广告ID' => $model->pro_id,
                '投诉人' => $model->complainant,
                '侵权原因' => $model->reason,
                '商标名' => $model->trademark,
                '知识产权编号' => $model->ip_number,
                '严重度' => $model->degree,
                '违规标号' => $model->violatos_number,
                '违规大类' => $model->violatos_big_type,
                '违规小类' => $model->violatos_small_type,
                '分值' => $model->score,
                '违规生效时间' => $model->violatos_start_time,
                '违规失效时间' => $model->violatos_fail_time,
                '违规状态' => $model->status ? '有效' : '无效',
                '销售' => $model->seller,
                '备注' => $model->remarks,
                '联系人' => $model->contact_name,
                '电话' => $model->phone,
                '邮箱' => $model->email,                
            ];
        }
        return $cellData;        
    }
    
    /**
     * 获取部分侵权数据
     * @param array $copyright_id_arr
     */
    public function exportPartData($copyright_id_arr){
        $data = $this->whereIn('id', $copyright_id_arr)->get();
        $cellData = array();
        foreach($data as $model){
            switch($model->plat){
                case '1' : $plat = 'ebay'; break;
                case '6' : $plat = 'smt' ; break;
                case '13': $plat = 'wish'; break;
            }
            
            $cellData[]  = [
                '帐号' => $model->account,
                '平台' => $plat,
                'SKU' => $model->sku,
                '广告ID' => $model->pro_id,
                '投诉人' => $model->complainant,
                '侵权原因' => $model->reason,
                '商标名' => $model->trademark,
                '知识产权编号' => $model->ip_number,
                '严重度' => $model->degree,
                '违规标号' => $model->violatos_number,
                '违规大类' => $model->violatos_big_type,
                '违规小类' => $model->violatos_small_type,
                '分值' => $model->score,
                '违规生效时间' => $model->violatos_start_time,
                '违规失效时间' => $model->violatos_fail_time,
                '违规状态' => $model->status ? '有效' : '无效',
                '销售' => $model->seller,
                '备注' => $model->remarks,
                '联系人' => $model->contact_name,
                '电话' => $model->phone,
                '邮箱' => $model->email,
            ];
        }
        return $cellData;  
    }
}
