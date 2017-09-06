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

Class MalaixiyaAdapter extends BasicAdapter
{
	private $tokenArr=array(
				1 =>array(//深圳仓账号
						'deToken'=>'02BC22555CBFF2D9B5E18ABD2D08B703',//Developer Token
						'userToken'=>'FB5AA09902BC22555CBFF2D952A29F315A7CA7F163A74EFAB7866751F9AA9D06'//User Token
					),
				2 =>array(//义乌仓账号
						'deToken'=>'9D1BFF12E7DF29A9A485D5BA651495DB',//Developer Token
						'userToken'=>'0A65884A9D1BFF12E7DF29A9B30398EB195708B650BC4CDA872AE72C949C4C6B',//User Token
					)
	);
	private $token;
	
    /*
	*Time:2016-07-25
	*malaixiya上传获取追踪号
	*@Jason
	*/
    public function getTracking($order_info)
    {
		
	   $result_arr['status']=0;
	   $result_arr['msg'] = '';
	   $upload_data = array();
	   $buyer_code = '';//国家二字简码
	  
	  //token
	  $this->token = $this->tokenArr[$order_info->warehouse_id];   //here warehouse
	  if($order_info->tracking_no != ''){
	    $result_arr['msg'] = '订单'.$order_info->order_id.'的追踪码已存在';
	    return $result_arr;
	  }
	  if(empty($order_info->shipping_country)){
	     $buyer_code = $order_info->shipping_country;
	  }else{
	     $buyer_code = $order_info->shipping_country;
	  }
	  if($order_info->shipping_country=='SRB' && $order_info->channel_id==6){
	    $buyer_code = 'RS';
	  }
	  
	  $shipmentCode = 'malaixiya';     //yw_channel here test 
	  //顺邮宝到英国的做特殊处理，uk改GB
	  if($buyer_code=='UK'){
	    $buyer_code = 'GB';
	  }
	  $buyer_address_1 = '';
	  $buyer_address_2 = '';
	  if($order_info->shipping_address==''){
	     $buyer_address_1 = $order_info->shipping_address;
	     $buyer_address_2 = '';
	  }else{
	     $buyer_address_1 = $order_info->shipping_address;
	     $buyer_address_2 = $order_info->shipping_address1;
	  }
	  
	  $order_info->order->shipmentCode = $shipmentCode;//物流代码(系统提供的固定字符)
	  $order_info->order->addr1 = $buyer_address_1;//地址1
	  $order_info->order->addr2 = $buyer_address_2;//地址1
	  $order_info->order->country = $buyer_code;//国家(目前只支持国家2字码)
	  
	  //new顺友data
	  $jsondata=$this->createOrderjson($order_info);
	  $url = 'http://a2.sunyou.hk/logistics/createAndConfirmPackages';
	  $result = $this->postCurlData($url,$jsondata);
	  $re = json_decode($result);
		$firstres=$re->data->resultList;//采用的都已单一订单上传，只用判断一个订单，
		$resultclass = $firstres[0];
		$result = $resultclass->processStatus;
		if($result == 'success'){
			//成功
			$orders_shipping_code = $resultclass->trackingNumber;//跟踪号
			$res = DB::table("packages")->where('id',$order_info->id)->update(
					['tracking_no' => serialize($orders_shipping_code),
					]);
			if($res){
		   	     $result_arr['msg'] = '订单号为'.$order_info->ordernum.'的订单上传成功，追踪码为'.$orders_shipping_code;
		   	  }else{
		   	     $result_arr['msg'] = '订单号为'.$order_info->ordernum.'的订单数据更新失败，追踪码为'.$orders_shipping_code;
		   	  }
	   	    $result_arr['status'] = 1;
		}else{
			$result = $resultclass->errorList;
			$data = $result[0];
		    $result_arr['msg'] = '订单号为'.$order_info->ordernum.'的订单上传失败,原因errorCode:'.$data->errorCode.',errorMsg:'.$data->errorMsg;
		}
	  return $result_arr;
  }
  
  //整合生成json数据
	public function createOrderjson($order_info){
		$products_with_battery = 0;//包裹电池属性0：不含电池,1：含电池,2：纯电池
		$products_with_powder = 0;//包裹粉末或液体属性，0：不包含，1：包含
		$products_with_food = 0;//目前公司不做食品，如果需要以后开发 
		$batterynum = 0;
		$total_weight = 0;
	    $total_count = 0;
		//产品信息str
		$productstr = '';
		foreach($order_info->items as $key => $item){
			$total_weight += $item->quantity * $item->item->weight;
	        $total_count += $item->quantity;
			// if($p['products_with_battery'] == 1){     //test here with battery  物流限制
				//此商品是电池
				// $batterynum++;
			// }
			// if($p['products_with_fluid'] == 1){
				//此商品有液体
				// $products_with_powder = 1;
			// }
			// if($p['products_with_powder'] == 1){
				//此商品有粉末
				// $products_with_powder = 1;
			// }
			//组装产品信息
			$productstr .='{
						"productSku": "'.$item->item->sku.'",
						"declareEnName": "'.$item->item->product->declared_en.'",
						"declareCnName": "'.$item->item->product->declared_cn.'",
						"quantity": '.$item->quantity.',
						"declarePrice": '.$item->item->product->declared_value.'
						},';
		}
		//循环结束
		$order_info->weight = $total_weight;//重量(float, 数值不能<=0.00)总重量
		
	    $order_info->decValue = round($order_info->order->amount/$total_count,2);//申报价值(float, 数值不能<=0.00)，订单总金额除以数量
		$productstr = trim($productstr,',');
		if(count($order_info->items)==$batterynum){
			//纯电池
			//$products_with_battery=2;
			$products_with_battery=1;//纯电池都设置为不纯电池，毛波林要求
		}elseif((count($order_info->items)>$batterynum) && ($batterynum>0)){
			//部分电池
			$products_with_battery=1;
		}
		$productsType = $products_with_battery.$products_with_powder.'0';
		$Token = $this->token;//使用账号token
			  //新顺友组装
			  $jsondata = '
			  	{
			  		"apiDevUserToken": "'.$Token['deToken'].'",
			  		"apiLogUsertoken": "'.$Token['userToken'].'",
			  		"data": {
						"packageList": [
						{
						"customerOrderNo": "'.$order_info->id.'",
						"shippingMethodCode": "'.trim($order_info->order->shipmentCode).'",
						"packageSalesAmount": '.$order_info->decValue.',
						"predictionWeight": '.$order_info->weight.',
						"recipientName": "'.$order_info->order->shipping_firstname.'",
						"recipientCountryCode": "'.$order_info->order->shipping_country.'",
						"recipientPostCode": "'.$order_info->order->shipping_zipcode.'",
						"recipientState": "'.$order_info->order->shipping_state.'",
						"recipientCity": "'.$order_info->order->shipping_city.'",
						"recipientStreet": "'.$order_info->order->addr1.' '.$order_info->order->addr2.'",
						"recipientPhone": "'.$order_info->order->shipping_phone.'",
						"recipientEmail": "'.$order_info->order->email.'",
						"senderName": "'.$order_info->logistics->emailTemplate->sender.'",
						"senderFullAddress": "'.$order_info->logistics->emailTemplate->address.'",
						"senderPhone": "'.$order_info->logistics->emailTemplate->phone.'",
						"senderPostCode": "'.$order_info->logistics->emailTemplate->zipcode.'",
						"insuranceFlag": "0",
						"packageAttributes": "'.$productsType.'",
						"productList": [
						'.$productstr.'
						]
				 		}
					]
				}
			}';
			return $jsondata;
	}
	/**
	 * Curl http Post 数
	 * 使用方法：
	 * $post_string = "app=request&version=beta";
	 * postCurlData('http://www.test.cn/restServer.php',$post_string);
	 */
	public function postCurlData($remote_server, $post_string) {
		$ch = curl_init(); 
		//$header[] = "SunYou-Token:{$this->token}";//定义header
		$header[] = 'Content-Type: application/json';//设置请求为json
		curl_setopt($ch, CURLOPT_URL, $remote_server); //定义表单提交地址 
		curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET 
		curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); //定义提交的数据，这里是XML文件 
		$tmpInfo = curl_exec ( $ch ); // 执行操作
		//echo '<pre/>';var_dump(curl_error($ch));exit;
		 curl_close($ch);//关闭
		 return $tmpInfo;
	}
	//获取物流渠道
	public function getShipment(){
	  $url = 'http://api.sunyou.hk/order/shiptype_list.htm';
	  $re = $this->getCurlData($url);
	  $result = json_decode($re);
	  return $result->result;
	}
	
}