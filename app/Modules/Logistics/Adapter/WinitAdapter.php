<?php
namespace App\Modules\Logistics\Adapter;


use App\Models\PackageModel;
class WinitAdapter extends BasicAdapter
{
    private $server_url ;
    private $userName ;
    private $passWord ;
    private $token;
    private $sign;
    protected $shipperAddrCode =array(
        999 =>'A00004',
        1000=>'SLME003',
        3 => 'SLME003',
    );
    
    protected $winitProductCode=array(
        '999' =>'WP-MYP002',
    );
    
    //YW10000008
    protected $warehouseCode =array(
        'WP-HKP101'=> 'YW10000008',
    );
    
    protected $winitProductCodeName =array(
        'WP-HKP001'=>'万邑邮选-香港渠道（平邮）',
        'WP-HKP002'=>'万邑邮选-香港渠道（挂号）',
        'WP-MYP001'=>'万邑邮选-马来西亚渠道（平邮）',
        'WP-EEP002'=>'万邑邮选-爱沙尼亚渠道（平邮）',
        'WP-EEP001'=>'万邑邮选-爱沙尼亚渠道（挂号）',
        'WP-SGP003'=>'万邑邮选-新加坡渠道（挂号）',
        'WP-SGP004'=>'万邑邮选-新加坡渠道（平邮）',
        'WP-NLP001'=>'万邑邮选-荷兰渠道（挂号）-含电',
        'WP-NLP011'=>'万邑邮选-荷兰渠道（挂号）-不含电',
    
        'WP-NLP002'=>'万邑邮选-荷兰渠道（平邮）-含电',
        'WP-NLP012'=>'万邑邮选-荷兰渠道（平邮）-不含电',
        'WP-CNP007'=>'万邑邮选-普通渠道（挂号）-北京',
        'WP-CNP004'=>'万邑邮选-普通渠道（平邮）-北京',
    
        'WP-SRP001'=>'万邑邮选-俄罗斯SPSR渠道（挂号）',
        'WP-FIP001'=>'万邑邮选-芬兰渠道（挂号）',
        'WP-DEP001'=>'万邑邮选-德国渠道（挂号）',
        'WP-DEP002'=>'万邑邮选-德国渠道（平邮）',
    
        'WP-CNP005'=>'万邑邮选-普通渠道（挂号）-上海',
        'WP-CNP006'=>'万邑邮选-普通渠道（平邮）-上海',
        'WP-HKP101'=>'万邑邮选-香港渠道（平邮）-eBay IDSE',
        'WP-MYP101'=>'万邑邮选-马来西亚渠道（平邮）-ebay IDSE',
    
        'WP-NLP101'=>'万邑邮选-荷兰渠道（平邮）-ebay IDSE-含电',
        'WP-NLP102'=>'万邑邮选-荷兰渠道（平邮） -eBay IDSE-不含电',
        'WP-DEP102'=>'万邑邮选-德国渠道（平邮香港）-ebay IDSE',
        'WP-DEP103'=>'万邑邮选-德国渠道（平邮上海）-ebay IDSE',
    );
    
    public function __construct($config){
        $this->userName = $config['userId'];
        $this->passWord = $config['userPassword'];
        $this->token = $config['key'];
        $this->server_url = $config['url']; 
    }
    
    public function getTracking($package)
    {              
        $creatOrder = array();
        
        list($name, $channel) = explode(',', $package->logistics->type);
        $creatOrder['buyerAddress1'] = preg_replace("/&|’|\/|'/",' ',$package->shipping_address);
        $creatOrder['buyerAddress2'] = preg_replace("/&|’|\/|'/",' ',$package->shipping_address1);
        $creatOrder['buyerCity'] = $package->shipping_city;
        $creatOrder['buyerContactNo'] = $package->shipping_phone;
        $creatOrder['buyerCountry'] = preg_replace("/&|’|\/|'/",' ',$package->shipping_country);
        $creatOrder['buyerEmail'] = $package->order->email;
        $creatOrder['buyerHouseNo'] = "";
        $creatOrder['buyerName'] = preg_replace("/&|’|\/|'/",' ',$package->shipping_firstname . " " . $package->shipping_lastname);
        $creatOrder['buyerState'] = $package->shipping_state;
        $creatOrder['buyerZipCode'] = $package->shipping_zipcode;
        $creatOrder['dispatchType'] = 'P';
        $creatOrder['ebaySellerId'] = $package->channelAccount ?  lcfirst($package->channelAccount->account) : '';
        
        $product_last = array();
        $product_detail = array();
        
        $product_last['weight'] = $package->signal_weight;
        foreach ($package->items as $key => $item) {                   
            if ($key == 0) {
                $product_last['height'] = (isset($item->item->height) && $item->item->height != 0)? $item->item->height : 5;
                $product_last['length'] = (isset($item->item->length) && $item->item->length !=0)? $item->item->length : 5;
                $product_last['width'] =  (isset($item->item->width) &&  $item->item->width !=0) ? $item->item->width  : 5;
                $product_detail['declaredNameCn'] = $item->item->product->declared_cn;
                $product_detail['declaredNameEn'] = $item->item->product->declared_en;
                $product_detail['declaredValue'] = $item->item->product->declared_value;
                $product_detail['itemID'] = $item->orderItem->orders_item_number;    //条目ID（eBay订单必填）  字段不确定，待确认
                $product_detail['transactionID'] = isset($item->orderItem->transaction_id) ? $item->orderItem->transaction_id : '0'; //交易ID（eBay订单必填） 不确定字段数据相关联的表
            }
            $product_last['merchandiseList'][] = $product_detail;
        }
        
        ksort($product_last);
        $creatOrder['packageList'][] = $product_last;               //包裹列表
        $creatOrder['pickUpCode'] = $package->order->ordernum;    //捡货条码
        $creatOrder['refNo'] = $package->order->ordernum;          //卖家订单号    字段不确定，待确认
        $logistics_code = $package->logistics->logistics_code;
        if($logistics_code == 524){
            $new_warehouseCode='YW10000012';//此渠道发金华仓
            $new_shipperAddrCode = 'YWSLME';
        }else{
            $new_warehouseCode=$this->warehouseCode[$channel];
            $new_shipperAddrCode=$this->shipperAddrCode[$package->warehouse_id];
        }
        /*$creatOrder['shipperAddrCode'] = $this->shipperAddrCode[$package->warehouse_id];        
        $creatOrder['warehouseCode'] = $this->warehouseCode['WP-HKP101']
        $creatOrder['winitProductCode'] = 'WP-HKP101';*/
       
        $creatOrder['shipperAddrCode'] = $new_shipperAddrCode;        
        $creatOrder['warehouseCode'] = $new_warehouseCode;
        $creatOrder['winitProductCode'] = $channel;
        echo "<pre>";
        print_r($creatOrder);
        $result = $this->callWinitApi("isp.order.createOrder",$creatOrder);
        
        print_r($result);
        $result = json_decode($result,true);
        if(isset($result['code'])&&($result['code']==0)&&($result['msg']=='操作成功'))        {   
              
            return array('code' => 'success', 'result' => $result['data']['orderNo'] );        
        }else{        
            return array('code' => 'error', 'result' => $result['msg']);
        }
    }
    
        
    public function getToken(){    
        $code = array();
        $code['action'] = "getToken";
        $code['data']['userName'] = $this->userName;
        $code['data']['passWord'] = $this->passWord;
        $url = 'http://erp.demo.winit.com.cn/ADInterface/api';
        return $this->curlPost($url,json_encode($code));        
    }
    
    public function getSign($token,$action,$data=''){
        $time =date('Y-m-d H:i:s');
        $string =$token.'action'.$action.'app_key'.$this->userName.'data'.(string)json_encode($data,JSON_UNESCAPED_UNICODE).'formatjsonplatformsign_methodmd5timestamp'.$time.'version1.0'.$token;   
        $sign =strtoupper(md5($string));
        $this->sign = $sign;
    
    }
    
    public function setApi($warehouse){
        $token=array(
            999=>'B512F5AFBE10C0D709BF7E0AF2B0C3B6',
            1000 =>'069C80E8D3E89D0618A98CE62DDE824A',
        );
        $userName =array(
            999=>'qiongjierui@163.com',
            1000=>'wuliu@moonarstore.com'
        );
        $passWord =array(
            999=>'888',
            1000=>'salamoer123456',
        );
        $server_url=array(
            999=>'http://openapi.demo.winit.com.cn/openapi/service',
            1000=>'http://openapi.winit.com.cn/openapi/service',
        );
        
        $this->passWord = $passWord[$warehouse];
        $this->userName = $userName[$warehouse];       
        $this->server_url =$server_url[$warehouse];
        $this->token = $token[$warehouse];
    }
    
    public function callWinitApi($action,$data='{}'){
        $post_array = array();
        $post_array['action']=$action;
        $post_array['app_key'] = $this->userName;
        $post_array['data'] =$data;
        $post_array['format'] ='json';
        $post_array['language'] ="zh_CN";
        $post_array['platform'] ="";
        $this->getSign($this->token,$post_array['action'],$post_array['data']);
        $post_array['sign'] =$this->sign;
        $post_array['sign_method'] ="md5";
        $post_array['timestamp'] =date('Y-m-d H:i:s');
        $post_array['version'] ="1.0";    
        $headers = array("application/x-www-form-urlencoded; charset=gb2312");
        //$result =  $this->curlPost($this->server_url,json_encode($post_array,JSON_UNESCAPED_UNICODE),$headers);
        $result = $this->postCurlData($this->server_url, json_encode($post_array,JSON_UNESCAPED_UNICODE));
        return $result;
    }
    
    /**
     * Curl http Post 数
     * 使用方法：
     * $post_string = "app=request&version=beta";
     * postCurlData('http://www.test.cn/restServer.php',$post_string);
     */
    public function postCurlData($remote_server, $post_string) {
        //   var_dump($post_string);exit;
        $ch = curl_init();
        $header[] = "Content-type: text/json";
        curl_setopt($ch, CURLOPT_URL, $remote_server); //定义表单提交地址
        curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET
        curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); //定义提交的数据，这里是XML文件
        $tmpInfo = curl_exec ( $ch ); // 执行操作
    
        curl_close($ch);//关闭
        return $tmpInfo;
    }
    
    
}

?>