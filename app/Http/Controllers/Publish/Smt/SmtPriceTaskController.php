<?php

namespace App\Http\Controllers\Publish\Smt;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtPriceTaskMain;
use App\Models\ChannelModel;
use App\Models\LogisticsModel;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductList;
use App\Models\Publish\Smt\smtPriceTask;
use App\Models\ProductModel;
use App\Models\CurrencyModel;
use App\Models\Logistics\ZoneModel;
use App\Models\ErpSalesPlatform;


class SmtPriceTaskController extends Controller
{
    public function __construct(){
        $this->mainTitle = 'SMT调价任务';
        $this->mainIndex = route('smtPriceTask.index');
        $this->viewPath = 'publish.smt.';        
        $this->model = new smtPriceTaskMain();
        $this->smt_price_task_model = new smtPriceTask();
        $this->channel_id = ChannelModel::where('driver','aliexpress')->first()->id;
    }
    
    public function index(){      
       $accountInfo = AccountModel::where('channel_id',$this->channel_id)->get();
       $logisticsInfo = LogisticsModel::all();      
       
       $shipmentArr = array();
       foreach($logisticsInfo as $logistics){
           $shipmentArr[$logistics->id] = $logistics->code;
       }
       
       $accountInfoArr = array();
       foreach($accountInfo as $account){
           $accountInfoArr[$account->id] = $account->alias;
       }       
       
       $response = [
           'metas' => $this->metas(__FUNCTION__),
           'data' => $this->autoList($this->model),
           'shipmentArr' => $shipmentArr,
           'accountInfoArr' => $accountInfoArr,  
           'accountInfo' => $accountInfo,
           'logisticsInfo' => $logisticsInfo,
         
       ];
       return view($this->viewPath . 'price_task_list', $response);       
    }
    
    /**
     * 批量删除调价任务
     */
    public function batchDelete(){
        $ids = request()->input('Ids');
        $IDArr = explode(',', $ids);
        $msg = '';
        foreach($IDArr as $id){
            $status = $this->model->where('id',$id)->first()->status;
            if($status != 1){
                $msg .= "{$id}的记录已经执行，不允许删除<br/>";
                continue;
            }
            $result = $this->model->where('id',$id)->delete();
            if($result){
                $msg .= "{$id}的记录已经删除<br/>";
            }else{
                $msg .= "{$id}的记录删除失败<br/>";
            }
        }
        
        return array('info' => $msg,true);
    }
    
    /**
     * 生成调价任务
     * @return multitype:string boolean
     */
    public function createPriceTask(){
        $post_data = request()->input();

        $data = array();
        $data['token_id'] = $post_data['token_id'];
        $data['shipment_id'] = $post_data['shipment_id'];
        $data['shipment_id_op'] = $post_data['shipment_id_op'];
        $data['percentage'] = $post_data['percentage'];
        $data['re_pirce'] = $post_data['re_pirce'];
        $data['status'] = 1;
        $data['group'] = $post_data['groupId'];
        $main_id = $this->model->create($data);
        if($main_id){
            $whereArr =array();
            $whereArr['token_id'] = $post_data['token_id'];
            $whereArr['isRemove'] = 0;
            $whereArr['productStatusType'] ='onSelling';
            if($post_data['groupId']){
                $whereArr['groupId'] = $post_data['groupId'];
            }
            
            $product_info = smtProductList::where($whereArr)->get();
            if($product_info){
                foreach($product_info as $re){
                    $info = array();
                    $info['productID'] = $re['productId'];
                    $info['account'] = $post_data['token_id'];
                    $info['status'] = 1;
                    $info['shipment_id'] = $post_data['shipment_id'];
                    $info['main_id'] =$main_id->id;
                    $this->smt_price_task_model->create($info);
                }
            }
        }
        return array('info' => '调价任务成功!',true);
    }
    
    /**
     * 执行调价任务
     */
    public function getSmtPriceTask(){
        $ids = request()->input('Ids');
        $idArr = explode(',', $ids);
        foreach($idArr as $id){
            $task_main_result = $this->model->where('id',$id)->first();
            
            $task_list_result = $this->smt_price_task_model->where(['main_id' => $id, 'status' => 1])->get();
            if($task_list_result){
                foreach($task_list_result as $task){
                    //获取渠道帐号信息
                    $account_info = AccountModel::findOrFail($task->account);
                    $smtApi = Channel::driver($account_info->channel->driver, $account_info->api_config);
                    
                    //根据产品ID获取线上商品详细信息
                    $api = 'api.findAeProductById';
                    $parameter ='productId='.rawurlencode($task->productID);
                    $result = $smtApi->getJsonData($api,$parameter);
                    $rs = json_decode($result,true);
                    //获取信息失败，更新该条调价任务的信息
                    if(isset($rs['error_code'])){   
                        $error = array();
                        $error['status'] = 3;
                        $error['remark'] = $rs['error_message'];
                        $error['api_time'] = date('Y-m-d H:i:s',time());
                        
                        $this->smt_price_task_model->where('id',$task->id)->update($error);
                        continue;
                    }
                    
                    $aeopAeProductSKUs = array();
                    $is_break = false; 
                    //遍历广告的SKU
                    foreach($rs['aeopAeProductSKUs'] as $key => $v){
                        $old_price = $v['skuPrice'];
                        $sku = $this->getSkuCode($v['skuCode']);
                        
                        $newSkuPrice = $this->getPriceByProfit($sku,$task_main_result['percentage'],$task_main_result['shipment_id'],$task_main_result['shipment_id_op']);
                        if(isset($newSkuPrice['error'])&&($newSkuPrice['error']=='error')){                     
                            $is_break= true;
                            break;
                        }
                        
                        $v['skuPrice'] =$newSkuPrice['price'];                                            
                        if( $v['skuPrice'] < $task_main_result['re_pirce']){
                            $v['skuPrice'] = $task_main_result['re_pirce'];
                        }
                        $v['skuPrice'] = (string)$v['skuPrice'];
                        $aeopAeProductSKUs[] = $v;
                    }
                    
                    if($is_break){ //跳过这个广告                                
                        $arr['status'] = 3;
                        $arr['shipment_id'] = isset($newSkuPrice['shipment_id']) ? $newSkuPrice['shipment_id'] : $task_main_result['shipment_id'];
                        $arr['re_pirce']  = isset($newSkuPrice['price']) ? $newSkuPrice['price'] : 0;
                        $arr['api_time'] = date('Y-m-d H:i:s',time());
                        $arr['remark'] = '新售价小于原售价10%,或者SKU有问题，未执行调价';
                        $this->smt_price_task_model->where('id',$task['id'])->update($arr);
                        continue;
                    }
                    
                    $rs['aeopAeProductSKUs'] = $aeopAeProductSKUs;                    
                    $post_arr = array();
                    $post_arr['productId']              = $rs['productId']; 
                    $post_arr['subject']                = $rs['subject'];
                    $post_arr['categoryId']             = $rs['categoryId'];
                    $post_arr['detail']                 = $rs['detail']; 
                    $post_arr['deliveryTime']           = $rs['deliveryTime'];     
                    $post_arr['productPrice']           = $rs['productPrice']; 
                    $post_arr['freightTemplateId']      = $rs['freightTemplateId']; 
                    $post_arr['isImageDynamic']         = $rs['isImageDynamic'] == 1 ? 'true' : 'false';
                    $post_arr['imageURLs']              = $rs['imageURLs']; 
                    $post_arr['productUnit']            = $rs['productUnit']; 
                    $post_arr['packageType']            = $rs['packageType'] ? 'true' : 'false'; //*
                    if($post_arr['packageType']){                    
                        $post_arr['lotNum']   = $rs['lotNum'];
                    }
                    $post_arr['packageLength']          = $rs['packageLength'];
                    $post_arr['packageWidth']           = $rs['packageWidth']; 
                    $post_arr['packageHeight']          = $rs['packageHeight']; 
                    $post_arr['grossWeight']            = $rs['grossWeight'];
                    $post_arr['wsValidNum']             = $rs['wsValidNum'];
                    $post_arr['isPackSell']             = $rs['isPackSell'];//新增的必要参数
                    $post_arr['reduceStrategy']        = $rs['reduceStrategy'];
                    $post_arr['currencyCode']           = $rs['currencyCode'];
                    $post_arr['aeopAeProductSKUs']      = json_encode( $rs['aeopAeProductSKUs']); 
                    $post_arr['aeopAeProductPropertys'] = json_encode($rs['aeopAeProductPropertys']);                                     
                    
                    $product_json = $smtApi->getJsonDataUsePostMethod( "api.editAeProduct", $post_arr);
                    $res = json_decode($product_json,true);
                    $update= array();
                    if(isset($res['success'])){                        
                        $update['status'] = 2;
                        $update['shipment_id'] = $newSkuPrice['shipment_id'];
                        $update['re_pirce']  = $newSkuPrice['price'];
                        $update['api_time'] = date('Y-m-d H:i:s',time());
                        $this->smt_price_task_model->where('id',$task['id'])->update($update);
                        unset($update);
                    }else{                                 
                        $update['status'] = 3;
                        $update['shipment_id'] = $newSkuPrice['shipment_id'];
                        $update['re_pirce']  = $newSkuPrice['price'];
                        $update['api_time'] = date('Y-m-d H:i:s',time());
                        $updater['remark'] = '未知错误';
                        if(isset($updateresult['error_message']))
                        {
                            $update['remark'] = $res['error_message'];
                        }
                        $this->smt_price_task_model->where('id',$task['id'])->update($update);
                        unset($update);
                    }                                      
                }                             
            }
            $arr = array();
            $arr['status'] =2;
            $this->model->where('id',$id)->update($arr);
        }
        return array('info'=>'执行完成','status'=>1);
    }
        
    /**
     * 从速卖通SKU中提取不带前后缀的SKU
     * @param $skuCode
     * @return mixed
     */
    public function getSkuCode($skuCode){
        $skuTemp  = $skuCode;
        $skuTempA = (strpos($skuTemp,"*") !== false) ? strpos($skuTemp,"*") : -1;
        $skuTempB = (strpos($skuTemp,"#") !== false) ? strpos($skuTemp,"#") : strlen($skuTemp);
        $skuTemp  = substr($skuTemp,$skuTempA+1,$skuTempB-$skuTempA-1);
        return $skuTemp;
    }
    
    /**
     * 根据广告利润率计算价格
     * @param unknown $sku
     * @param unknown $profitRate
     * @param unknown $shipmentId
     * @param unknown $shipment_id_op
     */
    public function getPriceByProfit($sku,$profitRate,$shipmentId,$shipment_id_op){
        $skuinfo = ProductModel::where('model',$sku)->first();
        if(empty($skuinfo)){ //没有找到这个SKU的信息        
            $arr=array();
            $arr['error'] = 'error';
            return $arr;
        }
        
        $currency_value = $this->getExchangeRateByType();        
        $shipment_id = $shipmentId;
        $cost = $skuinfo->purchase_price;
        if($skuinfo->logisticsLimit) {   //如果存在物流限制(电、液体、粉尘)  
            $shipment_id = $shipment_id_op;
        }
        
        $shipFee = $this->getShipFee($shipment_id,$skuinfo->weight);                 //计算运费   
        $platFeeRate  =  ErpSalesPlatform::where('platID',5)->first()->platFeeRate;     //获取速卖通平台费率       
        $exchangeRate = 1; // 速卖通是美元，汇率为1        
        $platFee = 0;
        
        $price= ((($cost + $shipFee) / $exchangeRate + $platFee)/(1- $profitRate/100-$platFeeRate/100))/$currency_value;
        
        $arr = array();
        $arr['price'] = round($price, 2);
        $arr['shipment_id']  = $shipment_id;
        
        return $arr;
    }
    
    /**
     * 根据币种求汇率
     * @param string $type
     */
    public function getExchangeRateByType($type = "RMB"){
        return CurrencyModel::where('code',$type)->first()->rate;
    }
    
    /**
     * 根据物流ID和重量计算运费
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getShipFee($id, $weight){
        $shipmentInfo = ZoneModel::where('logistics_id',$id)->first();
        if (empty($shipmentInfo)){
            return 0;
        }
        //$shipmentCalculateElementArray = unserialize($shipmentInfo['shipmentCalculateElementArray']);
        //运费 = 首重费用 + {[总重 - 首重] ÷ 续重} * 续重费用 + 操作费
        $firstFee         = $shipmentInfo->fixed_price;
        $firstWeight      = $shipmentInfo->fixed_weight;
        $additionalFee    = $shipmentInfo->continued_price;
        $additionalWeight = $shipmentInfo->continued_weight;
        $operateFee       = $shipmentInfo->price;
        $shipFee = $firstFee + ceil(($weight - $firstWeight) / $additionalWeight) * $additionalFee + $operateFee;
        return $shipFee;
    }
    
}
