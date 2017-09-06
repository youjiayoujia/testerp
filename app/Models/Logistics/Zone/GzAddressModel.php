<?php
/**
 * 广州平邮面单发件人地址表
 */
namespace App\Models\Logistics\Zone;

use Illuminate\Database\Eloquent\Model;

class GzAddressModel extends Model
{
    protected $table = "erp_gz_address";
    
    protected $fillable = ['useNumber','updateTime']; 
    
    /**
     * 获取符合条件的地址信息
     */
    public function getSenderInfoAttribute(){
        $now = date('Y-m-d');
        $first_sender_info = $this->where('id',1)->first(); 
        if($first_sender_info->updateTime != $now){
            $updateData = array('useNumber'=>0,'updateTime'=>$now);
            $this->where('id','>',0)->update($updateData);
        }
        
        $use_sender_info = $this->where('updateTime',$now)->where(function($query){
            $query->where('useNumber','<',50);
        })->first();
        
        if($use_sender_info){
            $addNumber = array('useNumber' => $use_sender_info->useNumber + 1);
            $this->where('id',$use_sender_info->id)->update($addNumber);
            return $use_sender_info;
        }
        return false;
    }
}
