<?php

namespace App\Console\Commands;

use DB;
use Channel;
use Illuminate\Console\Command;
use App\Models\PackageModel;
use App\Models\Channel\AccountModel;
use App\Models\Log\CommandModel as CommandLog;
class uploadSmtOrderOnline extends Command
{
    /**
     * The name and signature of the console command.
     *  
     * @var string
     */
    protected $signature = 'uploadSmtOrderOnline:do{logistics_id}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '速卖通线上发货下单';
    
    /**
     * SMT线上发货的发货地址
     * @var array
     */
    private  $_senderAddress;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_senderAddress = array(
            '1' =>  array(
                'sender' => array(
                    'country'       => 'CN',        //国家简称
                    'province'      => 'GUANGDONG', //省/州,（必填，长度限制1-48字节）
                    'city'          => 'SHENZHEN',  //城市
                    'streetAddress' => '2nd Floor, Buliding 6,No. 146 Pine Road , Mengli Garden Industrial, Longhua District ,Shenzhen ，Guangdong, China', //街道 ,（必填，长度限制1-90字节）
                    'phone'         => '18038094536', //phone（长度限制1- 54字节）,phone,mobile两者二选一
                    //'mobile'        => '',          //mobile（长度限制1-30字节）
                    'name'          => 'huangchaoyun', //姓名,（必填，长度限制1-90字节）
                    'postcode'      => '518129'  //邮编
                ),
                'pickup' => array(           //深圳仓揽货地址，写中文
                    'country'       => 'CN',                     //国家简称
                    'province'      => '广东省',                   //省/州,（必填，长度限制1-48字节）
                    'city'          => '深圳市',                   //城市
                    'county'        => '龙华新区',                 //区
                    'streetAddress' => '油松路146号梦丽园工业园6栋2楼', //街道 ,（必填，长度限制1-90字节）
                    'phone'         => '18038094536',           //phone（长度限制1- 54字节）,phone,mobile两者二选一
                    //'mobile'        => '',                    //mobile（长度限制1-30字节）
                    'name'          => '黄超云',                  //姓名,（必填，长度限制1-90字节）
                    'postcode'      => '518129'                 //邮编
                )
            ),
            '2' => array(
                'sender' => array( //义乌金华仓发货地址,必须是英文
                    'country'       => 'CN', //国家简称
                    'province'      => 'ZHEJIANG', //省/州,（必填，长度限制1-48字节）
                    'city'          => 'JINHUA', //城市
                    'streetAddress' => 'Buliding 1-2, Jinyi Postal Park, No.2011, JinGangDaDao West, JINDONG', //街道 ,（必填，长度限制1-90字节）
                    'phone'         => '13715115766', //phone（长度限制1- 54字节）,phone,mobile两者二选一
                    'name'          => 'liubaojun', //姓名,（必填，长度限制1-90字节）
                    'postcode'      => '321000'  //邮编
                ),
                'pickup' => array( //义务金华仓揽货地址，写中文
                    'country'       => 'CN', //国家简称
                    'province'      => '浙江省', //省/州,（必填，长度限制1-48字节）
                    'city'          => '金华市', //城市
                    'county'        => '金东区', //区，必填
                    'streetAddress' => '金东傅村镇金义都市新区金港大道 2011号（金义邮政电子商务示范园）1号二楼', //街道 ,（必填，长度限制1-90字节）
                    'phone'         => '13715115766', //phone（长度限制1- 54字节）,phone,mobile两者二选一
                    'name'          => '刘保军', //姓名,（必填，长度限制1-90字节）
                    'postcode'      => '321000'  //邮编
                )
            )
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $logistics_id = $this->argument('logistics_id');    //需要标记发货的SMT渠道ID：369,379,517,518,525,526,527,528,542,541
        //获取速卖通、追踪号为空的、包裹状态为待分配的包裹信息
       /*$package_list = PackageModel::where(['channel_id'=>2,'is_upload'=>0,'tracking_no'=>'','logistics_id'=>$logistics_id])
            ->where(function($query){
                $query->where('status','WAITASSIGN')
                    ->orWhere(function($query){
                        $query->where('status','NEED')
                            ->where('updated_at','<=',date('Y-m-d H:i:s' , strtotime("-3 day")));
                });
            })->get();*/
       $package_list = PackageModel::where(['channel_id'=>2,'status'=>'WAITASSIGN','is_upload'=>0,'tracking_no'=>'','logistics_id'=>$logistics_id])->get();
       if(count($package_list)){
           foreach($package_list as $package){
               $start = microtime(true);
               $total = 0;
               $commandLog = CommandLog::create([
                   'relation_id' => $account->id,
                   'signature' => __CLASS__,
                   'description' => 'SMT线上发下单!',
                   'lasting' => 0,
                   'total' => 0,
                   'result' => 'init',
                   'remark' => 'init',
               ]);
               $log = array();
               $log['data'] = 'SMT线上发下单';
               $isMatch = false;
               $account = AccountModel::findOrFail($package->channel_account_id);
               $smtApi = Channel::driver($account->channel->driver, $account->api_config);
               //获取订单支持的物流渠道
               $LogisticsServiceList = $smtApi->getOnlineLogisticsServiceListByOrderId($package->order->channel_ordernum);
               if(array_key_exists('success',$LogisticsServiceList) && $LogisticsServiceList['success']){
                   foreach($LogisticsServiceList as $LogisticsService){
                       if($LogisticsService['logisticsServiceId'] == $package->logistics->logistics_code){
                            $isMatch = true;                
                       }
                   }
               }
               if($isMatch){
                   $productData = array();
                   $productData[] = array(
                       'categoryCnDesc'         => $package->decleared_cname ? $package->decleared_cname : '连衣裙',
                       'categoryEnDesc'         => $package->decleared_ename ? $package->decleared_ename : 'dress',
                       'productDeclareAmount'   => $package->decleared_value,
                       'isContainsBattery'      => $package->is_battery ? 1 : 0,
                       'productId'              => $package->order->items->orders_item_number,
                       'productNum'             => $package->items ? $package->items->first()->quantity : 0,
                       'productWeight'          => $package->total_weight
                   );
                   
                   $data = array();
                   $data['tradeOrderId'] = $package->order->channel_ordernum;
                   $data['tradeOrderFrom']  = 'SOURCING';              //订单来源;AE订单为ESCROW ；国际站订单为“SOURCING”
                   $data['warehouseCarrierService'] = $package->logistics->logistics_code;                                   
                   $data['domesticLogisticsCompanyId'] = '-1';        //国内快递ID;(物流公司是other时,ID为-1)
                   $data['domesticLogisticsCompany']   = '上门揽收';    //国内快递公司名称;(物流公司Id为-1时,必填)
                   $data['domesticTrackingNo']         = 'None';     //国内快递运单号,长度1-32
                    
                   $addressArray = array(
                       'receiver' => array(
                           'country'       => $package->shipping_country,  //国家简称
                           'province'      => $package->shipping_state,
                           'city'          => $package->shipping_city,
                           'streetAddress' => trim($package->shipping_address . ' ' . $package->shipping_address1),
                           'phone'         => $package->shipping_phone,
                           'name'          => $package->shipping_firstname.' '.$package->shipping_lastname,
                           'postcode'      => $package->shipping_zipcode
                       ),
                   );
                   
                   //获取SMT平台线上发货地址
                   $address_smt = array('request'=>'["sender","pickup"]');
                   $address_return = $smtApi->getJsonDataUsePostMethod('alibaba.ae.api.getLogisticsSellerAddresses',$address_smt);
                   $address_return = json_decode($address_return,true);
                   if(array_key_exists('success', $address_return) && $address_return['success']){
                       $addressArray['sender']['addressId'] = $address_return['senderSellerAddressesList'][0]['addressId'];
                       $addressArray['pickup']['addressId'] = $address_return['pickupSellerAddressesList'][0]['addressId'];
                   }
                   $addressArray = array_merge($addressArray, $this->_senderAddress[$package->warehouse_id]);
                   $data['declareProductDTOs'] = json_encode($productData);
                   $data['addressDTOs'] = json_encode($addressArray);
                   
                   $result = $smtApi->getJsonDataUsePostMethod('api.createWarehouseOrder',$data);
                   $result = json_decode($result,true);
                   if(array_key_exists('success',$result) && $result['success']){
                       if($result['result']['success']){
                           if(array_key_exists('intlTracking', $result['result']) && $result['result']['intlTracking']){//有挂号码就要返回，不然还得再调用API获取
                               PackageModel::where('id',$package->id)->update(['tracking_no'=>$result['result']['intlTracking'],'is_upload'=>1]);
                               $log['status'] = 'success';
                               $log['remark'] = '下单成功!';
                           }else{
                               PackageModel::where('id',$package->id)->update(['logistics_order_number'=>$result['result']['warehouseOrderId']]);
                               $log['status'] = 'success';
                               $log['remark'] = '平台未返回挂单号!';
                           }
                       }else{
                           $log['status'] = 'fail';
                           $log['remark'] = '订单号：'.$package->order->channel_ordernum.'下单失败, errorCode为：'.$result['result']['errorCode'];
                       }
                   }else{
                        $log['status'] = 'fail';
                        $log['remark'] = '存在不符合的数据项:'.var_export($result,true); 
                   }
               }
               $end = microtime(true);
               $lasting = round($end - $start, 3);
               $commandLog->update([
                   'data' => $log['data'],
                   'lasting' => $lasting,
                   'total' => $total,
                   'result' => $log['status'],
                   'remark' => $log['remark'],
               ]);
           }
       }
    }
}
