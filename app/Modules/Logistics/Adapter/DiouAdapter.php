<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2016-07-26
 * Time: 14:23
 */
namespace App\Modules\Logistics\Adapter;
header("Content-type:text/html;charset=utf-8");
use App\Models\Order\ItemModel;

use App\Models\PackageModel;

use Illuminate\Support\Facades\DB;

set_time_limit(1800);

Class DiouAdapter extends BasicAdapter
{
	private $_server_url;
	private $_soapClient;
    protected $token="8wmw5OrBmOzq8aQ3BB0ifQ==";
    protected $CustomerCode = "DO00038";
    public function __construct(){
    		$this->_server_url = 'http://www.dioexpress.com:81/Order/CreateOrder?OrderInfo=';
            
    }
    /*
	*Time:2016-07-25
	*diou物流
	*@Jason
	*/
    public function getTracking($orderInfo)
    {
		$status = "no";
    	$err_msg = '';
    	if (!$orderInfo->id) {
            return $result = [
                'code' => 'error',
                'result' => 'The data is empty！'
            ];
    	}
		$orderdata = $this->createOrderdata($orderInfo);

		$url =$this->_server_url.urlencode($orderdata);
    	$result=$this->postCurlData( $url);
        $result =json_decode($result,true);print_r($result);
        if($result['Ack'] == 'Success'){
            return $result = [
                'code' => 'success',
                'result' => isset($result['Trackingnumber']) ? $result['Trackingnumber'] : ''
            ];
        }else{
            return $result = [
                'code' => 'error',
                'result' => $result['Message']
            ];
        }
	}
	//创建订单所需要的数据
    public function createOrderdata($orderInfo){

		$total_weight = 0;
        $total_count = 0;	
        $products="[";
        $channel_arr = array(502,508);
        if(!in_array($orderInfo->logistics->logistics_code,$channel_arr)){    //渠道是否符合
            return $result = [
                'code' => 'error',
                'result' => 'channel error！'
            ];
        }
        foreach($orderInfo->items as $key => $item){
            $total_weight += $item->quantity * $item->item->weight;
            $total_count += $item->quantity;
            //组装产品信息
            $products .= '
                    {
                        "DeclarationCNName":"'.$item->item->product->declared_cn.'",
                        "DeclarationENName":"'.$item->item->product->declared_en.'",
                        "DeclarationPrice":'.$item->item->product->declared_value.',
                        "Qty":'.$item->quantity.'
                    }
                ';
            if((count($orderInfo->items)-1) > $key){
                $products .=',';
            }
        }

        $products .="]";
        $products = str_replace("\r",'',$products);
        $products = str_replace("\n",'',$products);
        $products = trim($products,',');
        $orderInfo['weight'] = $total_weight;//重量(float, 数值不能<=0.00)总重量
        $orderInfo['decValue'] = round($orderInfo->order->amount/$total_count,2);//申报价值(float, 数值不能<=0.00)
        $buyer_address_1 = '';
		
      $buyer_address_2 = '';
      if($orderInfo->shipping_address==''){
         $buyer_address_1 = $orderInfo->shipping_address1;
         $buyer_address_2 = '';
      }else{
         $buyer_address_1 = $orderInfo->shipping_address;
         $buyer_address_2 = $orderInfo->shipping_address1;
      }
        $json='[{
            "CustomerCode":"'.$this->CustomerCode.'",
            "Token":"'.$this->token.'",
            "CustomerOrderId":"'.$orderInfo->id.'",
            "RecipientName":"'.$orderInfo->shipping_firstname.'",
            "Province":"'.$orderInfo->shipping_firstname.'",
            "City":"'.$orderInfo->shipping_city.'",
            "PostCode":"'.$orderInfo->shipping_zipcode.'",
            "PhoneNum":"'.$orderInfo->shipping_phone.'",
            "Address":"'.$buyer_address_1.$buyer_address_2.'",
            "Country":"'.$orderInfo->country->cn_name.'",
            "CountryCode":"'.$orderInfo->country->code.'",
            "ParcelPcs":1,
            "ShippingMethodKey":56,
            "Company":"'.$orderInfo->shipping_firstname.'",
            "IsRegister":false,
            "Email":"'.$orderInfo->email.'",
            "Weight":"'.$orderInfo->weight.'",
            "OrderDetails":'.$products.'
            }]';
            $json = str_replace("\r",'',$json);
            $json = str_replace("\n",'',$json);
        return $json;
    }
        public function postCurlData($remote_server) {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $remote_server); //定义表单提交地址 
        curl_setopt($ch, CURLOPT_POST, 0);   //定义提交类型 1：POST ；0：GET 
        curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流 
        $tmpInfo = curl_exec ( $ch ); // 执行操作
         curl_close($ch);//关闭
         return $tmpInfo;
    }
	
}