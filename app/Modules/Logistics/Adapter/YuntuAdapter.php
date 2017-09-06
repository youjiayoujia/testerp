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

use App\Models\Publish\Wish\WishSellerCodeModel;

use Illuminate\Support\Facades\DB;

set_time_limit(1800);

Class YuntuAdapter extends BasicAdapter
{
	
    public function __construct($config)
    {
        
    }

    /*
	*Time:2016-07-25
	*yuntu上传获取追踪号
	*@Jason
	*/
    public function getTracking($ordersinfo)  
    {
		$credentials = "C10262&8EE7CxtoZ2c=";  //帐号：密码		
		$declare_value = ($ordersinfo->order->amount>20) ? 20 : $ordersinfo->order->amount;//申报价值即是订单总金额除以sku的数量（向上取整,保留两位一位），最高不超过20没有
		if(isset($ordersinfo->logistics_order_number) && $ordersinfo->logistics_order_number !== '' && $ordersinfo->tracking_no == ''){
			$res = $this->yunTuGetTrackNumApi($ordersinfo->id);
			if(isset($res['ResultCode']) && $res['ResultCode']=='0000'){
				return $result = [
						'code' => 'success',
						'result' => isset($res['Item'][0]['TrackingNumber']) ? $res['Item'][0]['TrackingNumber'] : ''
				];
			}

		}
		if(isset($ordersinfo->logistics_order_number) && $ordersinfo->logistics_order_number !== '' && isset($ordersinfo->tracking_no) && $ordersinfo->tracking_no !== ''){
			return $result = [
					'code' => 'error',
					'result' => 'Purple tracking number already exists'
			];
		}
		foreach($ordersinfo->items as $key => $item){
			$declare_name_cn = $item->item->product->declared_cn;//申报中文名称是第一个产品的中文申报名称
			$declare_name_en = $item->item->product->declared_en;//申报英文名称是第一个产品的英文申报名称
			break;
		}
		$sku_json = array();	//产品信息	
		foreach($ordersinfo->items as $key => $item){	//here test
			$deValue = 0;
			$deValue = floor($declare_value/$item->quantity)<1 ? 1 : floor($declare_value/$item->quantity);
			$num = $deValue * $item->quantity;
			if($num > 20){
				$deValue = 20/$item->quantity;
			}
                         $sku_json[] = '{				  
			  "ApplicationName": "'.$declare_name_en.'",
			  "HSCode": "'.trim($item->item->product->model).'",
			  "Qty": '.$item->quantity.',
			  "UnitPrice":'.$deValue.',
			  "UnitWeight": '.$ordersinfo->weight.',
			  "PickingName": "'.$declare_name_cn.'",
			  "Remark":"'.trim($item->item->product->model).'",
			  "ProductUrl":"www.baidu.com"				  
			}';	 
			break;//只需要第一个 申报SKU信息  老板要求
		}
		$CountryCode = $ordersinfo->shipping_country;//收件人国家代码
		
		//收件人姓名的处理
		$buyer_name = preg_replace('/[0-9-\/]/',' ',$ordersinfo->order->shipping_firstname);//去掉数字和-和/的正则表达式
		
		$buyer_name = addslashes($buyer_name);
		
		$buyer_name = preg_replace('/&[a-z]+;/','',$buyer_name);
		
		$sex = '';//存放姓
		
		$name = '';//存放名
		
		$nameArr = explode(' ',$buyer_name);  //test here  data
		if(count($nameArr)==1){
			$sex = $nameArr[0];
			$name = $nameArr[0];
		}else{
			$sex = $nameArr[0];
			unset($nameArr[0]);
			$name = implode(' ',$nameArr);
		}
		if(strtoupper(trim($CountryCode)) == 'UK'){
			$CountryCode = 'GB';
		}
		if(strtoupper(trim($CountryCode)) == 'SRB'){
			$CountryCode = 'RS';
		}
		//收件人地址
		$ShippingAddress1 = "";
		$ShippingAddress1 = addslashes($ordersinfo->shipping_address).' '.addslashes($ordersinfo->shipping_address1);
		
		$buyer_state = !empty($ordersinfo->order->shipping_state) ? $ordersinfo->order->shipping_state : $ordersinfo->order->shipping_city;
		$request_json='';
		$request_json.='[
							{	 
								  "ApplicationInfos": [';
								$request_json .= implode(",",$sku_json);
								$request_json.='    
								  ], 
								  "OrderNumber": "SLMORES'.$ordersinfo->id.'",
								  "TrackingNumber": "'.$ordersinfo->logistics_order_number.'",
								  "ShippingMethodCode": "'.$ordersinfo->logistics->type.'",
								  "ApplicationType": 4,
								  "Weight": 1,
								  "PackageNumber": 1,
								  "ShippingInfo": {
										"CountryCode": "'.strtoupper($CountryCode).'",
										"ShippingFirstName": "'.$sex.'",
										"ShippingLastName": "'.$name.'",
										"ShippingAddress": "'.addslashes($ShippingAddress1).'",
										
										"ShippingCity": "'.$ordersinfo->shipping_city.'",
										"ShippingState": "'.$buyer_state.'",
										"ShippingZip": "'.$ordersinfo->shipping_zipcode.'",
										"ShippingPhone": "'.$ordersinfo->shipping_phone.'"
								  },
								  "SenderInfo": {
										"CountryCode": "",
										"SenderAddress": "",
										"SenderCity": "",
										"SenderCompany": "",
										"SenderFirstName": "",
										"SenderLastName": "",
										"SenderPhone": "",
										"SenderState": "",
										"SenderZip": ""
									}								  								  
							}
		 				]';
		
		$url = "http://api.yunexpress.com/LMS.API/api/WayBill/BatchAdd";
		
		$headers = array(
				"Authorization: Basic ".base64_encode($credentials),
				"Content-type: application/json;charset=UTF-8"		
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);	
		$data = curl_exec($ch);		
		$reArr = array();
		if (curl_errno($ch)) {
			$reArr['Error'] = curl_error($ch);
			return $reArr;
		}else{
			curl_close($ch);		
			$data = json_decode($data,true);print_r($data);
			if(isset($data['Item'][0]['OrderId']) && isset($data['Item'][0]['WayBillNumber']) && $data['Item'][0]['OrderId'] !== '' && $data['Item'][0]['WayBillNumber'] !== ''){
				return $result = [
						'code' => 'success',
						'result' => isset($data['Item'][0]['WayBillNumber']) ? $data['Item'][0]['WayBillNumber'] : '',
						'result_other' => isset($data['Item'][0]['OrderId']) ? $data['Item'][0]['OrderId'] : ''
				];
			}else if(isset($data['Item'][0]['WayBillNumber']) && $data['Item'][0]['WayBillNumber'] !== ''){
				return $result = [
						'code' => 'again',
						'result' => isset($data['Item'][0]['WayBillNumber']) ? $data['Item'][0]['WayBillNumber'] : '',
						'result_other' => isset($data['Item'][0]['OrderId']) ? $data['Item'][0]['OrderId'] : ''
				];
			}else{
				return $result = [
						'code' => 'error',
						'result' => isset($data['Item'][0]['Feedback']) ? $data['Item'][0]['Feedback'] : ''
				];
			}
		}		
    }
	/**
	 * 根据订单号获取跟踪号
	 * @param unknown $ordersInfoArr
	 */
	public function yunTuGetTrackNumApi($ordersInfoArr){
		$credentials = "C10262&8EE7CxtoZ2c=";  //帐号：密码
		$url = "http://gapi.yunexpress.com/api/WayBill/GetTrackNumber?orderId=SLMERS".$ordersInfoArr;
		$headers = array(
				"Authorization:Basic ".base64_encode($credentials),
				"Content-type: application/json;charset=UTF-8"
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$data = curl_exec($ch);
		$reArr = array();
		if (curl_errno($ch)) {
			$reArr['Error'] = curl_error($ch);
			return $reArr;
		}else{
			curl_close($ch);
			$data = json_decode($data,true);
			return $data;
		}
	}
    
}