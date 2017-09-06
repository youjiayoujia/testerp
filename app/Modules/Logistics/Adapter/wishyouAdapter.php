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

use App\Models\AccounttokenModel;

use App\Models\Publish\Wish\WishSellerCodeModel;

use Illuminate\Support\Facades\DB;

set_time_limit(1800);

Class WishyouAdapter extends BasicAdapter
{
	public  $senderInfo;//发件人信息
	private $API_key;//密钥

	//wish邮的分仓代码
	private $warehouseArr = array(
								'sh'=>1,//上海仓
								'gz'=>2,//广州仓
								'sz'=>3,//深圳仓  4义乌
								'yw'=>4
							);
							
    //寄件人和揽收人地址（中英文不同）
    private $senderAndLanShou = array(
       'sz' => array(
            'cn' => array(
	    		  'province' => '广东',
				  'city'     => '深圳',
				  'username' => '萨拉摩尔',
				  'phone'    => '18038094536',
				  'address'  => '龙岗区五和大道南41号和磡工业区A3栋二楼'
    		),
    		'en' => array(
	    		  'province' => 'guangdong',
				  'city'     => 'shenzhen',
				  'username' => 'slme',
				  'phone'    => '18038094536',
				  'address'  => 'A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist'
    		)
        ),
		'gz' => array(
            'cn' => array(
	    		  'province' => '广东',
				  'city'     => '深圳',
				  'username' => '萨拉摩尔',
				  'phone'    => '18038094536',
				  'address'  => '龙岗区五和大道南41号和磡工业区A3栋二楼'
    		),
    		'en' => array(
	    		  'province' => 'guangdong',
				  'city'     => 'shenzhen',
				  'username' => 'slme',
				  'phone'    => '18038094536',
				  'address'  => 'A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist'
    		)
        ),
        'yw' => array(
            'cn' => array(
	    		  'province' => '浙江',
				  'city'     => '金华',
				  'username' => '萨拉摩尔',
				  'phone'    => '17705794166',
				  'address'  => '浙江省金华市金东区鞋塘镇金港大道西2011号金义邮政电子商务示范园'
    		),
    		'en' => array(
	    		  'province' => 'zhejiang',
				  'city'     => 'jinhua',
				  'username' => 'slme',
				  'phone'    => '17705794166',
				  'address'  => 'Jin Dong District, Fu Village, town, West 2011, Jin Hong Kong Road West 1 building two'
    		)
        ),
        'sh' => array(
            'cn' => array(
	    		  'province' => '',
				  'city'     => '',
				  'username' => '',
				  'phone'    => '',
				  'address'  => ''
    		),
    		'en' => array(
	    		  'province' => 'shanghai',
				  'city'     => 'shanghai',
				  'username' => 'slme',
				  'phone'    => '18038094536',
				  'address'  => 'No.1208 Baise Road,Wish storehouse,Xuhui District Shanghai China,200237'
    		)
        )
    );

	private $channel_info = array('370'=>'wish邮,yw,sh,0','469'=>'wish邮,yw,yw,0','481'=>'wish邮,sz,sz,0','487'=>'wish邮,sz,sz,11-0','512'=>'wish邮,sz,gz,0','549'=>'wish邮,yw,nj,0');


	public function __construct($config)
    {
        
    }

    /*
	*Time:2016-07-25
	*Upload postal tracking numbers wish to obtain
	*@Jason
	*/
    public function getTracking($orderInfo)
    {

		if(!$orderInfo){
			return $result = [
				'code' => 'error',
				'result' => 'empty data!'
			];
	    }
		$token_arr = AccounttokenModel::where('type','wish_you')->get();//wish_you账号token
		$this->tokenArr = array();
		if(!empty($token_arr)){
            foreach($token_arr as $token){
				if($token->account == 'slme'){
					$this->tokenArr['1'] = $token->account_token;
					$this->tokenArr['3'] = $token->account_token;
				}else{
					$this->tokenArr['2'] = $token->account_token;
					$this->tokenArr['4'] = $token->account_token;
				}
			}
		}
		$this->API_key = $this->tokenArr[$orderInfo->warehouse_id];  //根据仓库选择对应

		$result_arr['status'] = 0;
	 	$result_arr['msg'] = '';
		$otype = '0';

		if(isset($orderInfo->logistics_order_number) && $orderInfo->logistics_order_number !== '' && isset($orderInfo->tracking_no) && $orderInfo->tracking_no !== ''){
			return $result = [
				'code' => 'error',
				'result' => 'Purple tracking number already exists'
			];
		}
		if(!$orderInfo->warehouse_id){
			return $result = [
				'code' => 'error',
				'result' => 'warehouse_id is null'
			];
		}
		$shipInfo = explode(',',$this->channel_info[$orderInfo->logistics->logistics_code]);
		if($shipInfo[0] != 'wish邮'){
			return $result = [
				'code' => 'error',
				'result' => 'channel error'
			];
		}
		//WISH邮的渠道代码
		/* WISH邮平邮=0
		WISH邮挂号=1
		DLP平邮=9-0
		DLP挂号=9-1
		DLE=10-0
		E邮宝=11-0
		欧洲经济小包=200-0
		欧洲标准小包=201-0*/
		$otype = $shipInfo[3];
		$wish_type = array('0','1','9-0','9-1','10-0','11-0','200-0','201-0');
		if(!isset($otype)  || !in_array($otype,$wish_type)){
			return $result = [
				'code' => 'error',
				'result' => 'Channel code error'
			];
		}





		$order_xml=$this->createRequestXmlFile($orderInfo,$shipInfo);  //xml
		print_r($order_xml);
		 if(!$order_xml){
			 return $result = [
				 'code' => 'error',
				 'result' => 'xml error'
			 ];
		 }
		$url = 'https://wishpost.wish.com/api/v2/create_order';

	   $call=$this->postCurlData($url,$order_xml);

	   $result=simplexml_load_string($call);
	   $mess = '';print_r($result);
	   foreach($result as $key=>$v){
	   		if(preg_match("/error/",$key)){
	   			$mess = $v;
	   		};
	   }
	   if( ($result->status == 0) && !empty($result->barcode) ){
	   	  $result->barcode = trim($result->barcode);
		   return $result = [
			   'code' => 'success',
			   'result' => isset($result->barcode) ? $result->barcode : ''
		   ];
	   }else{
	   		if($result->error_message == ''){
	   			$result->error_message = $mess;
	   		}
		   return $result = [
			   'code' => 'error',
			   'result' => $result->error_message
		   ];
	   }
    }
	/**
	 * 构造添加订单的请求xml文件
	 */
	public function createRequestXmlFile($orderInfo,$shipInfo){

		 $xmlStr = '<?xml version="1.0" encoding="UTF-8"?>';
		 $xmlStr .='
		 			<orders>
		 			   <access_token>'.$this->API_key.'</access_token>
		 			  <mark></mark>
		 			  <bid>'.rand(0,99999).'</bid>
		 		   ';
			$buyer_address = $orderInfo->shipping_address.' '.$orderInfo->shipping_address1;  //test here two address

		    $buyer_country = $orderInfo->country ? $orderInfo->country->name : '';
		    $buyer_country_code = $orderInfo->country ? $orderInfo->country->code : '';

		    $total_count = 0;//内件数量
		    $total_weight = 0;//货品重量，千克（三位小数）
		    $total_value = 0;//货品申报价值(两位小数)
		    $content = '';//内件物品的详细名称（英文）
		    $buyer_phone = '000000';//电话默认为0
		    if(!empty($orderInfo->shipping_phone)){
		      $buyer_phone=$orderInfo->shipping_phone;
		    }
			foreach($orderInfo->items as $k=>$v){
				$order_item = $v->orderItem;
				$wishIDArr = explode('+',$order_item->channel_order_id);
				$wishID = $wishIDArr[0];              //wish_id
			}
			if(!$wishID){
				$wishID = $orderInfo->id;
			}

		    $buyer_state = $orderInfo->shipping_city ? $orderInfo->shipping_city : ',';//上传的省，默认是用城市名代替
		    
		    //上传的省为空用逗号代替
		    $buyer_state = $orderInfo->shipping_state ? $orderInfo->shipping_state :',';
		     foreach($orderInfo->items  as $key => $item){
		      $total_count += $item->quantity;
		      $total_weight += $item->quantity*$item->item->weight;
		    }

		    $total_value = round($orderInfo->order->amount,2);
		    $content = $item->item->product->declared_en;   //申报英文名  test $allNeedData['productsInfo'][0]['products_declared_en']

		    $xmlStr .='
		      <order>
		        <guid>'.$orderInfo->id.'</guid>
		        <otype>'.$shipInfo[3].'</otype>
		        <from>'.$this->senderAndLanShou[$shipInfo[1]]['en']['username'].'</from>
		        <sender_province>'.$this->senderAndLanShou[$shipInfo[1]]['en']['province'].'</sender_province>
		        <sender_city>'.$this->senderAndLanShou[$shipInfo[1]]['en']['city'].'</sender_city>
		        <sender_addres>'.$this->senderAndLanShou[$shipInfo[1]]['en']['address'].'</sender_addres>
		        <sender_phone>'.$this->senderAndLanShou[$shipInfo[1]]['en']['phone'].'</sender_phone>
		        <to>'.$orderInfo->shipping_firstname.'</to>
		        <recipient_country>'.$buyer_country.'</recipient_country>
		        <recipient_country_short>'.$buyer_country_code.'</recipient_country_short>
		        <recipient_province>'.$buyer_state.'</recipient_province>
		        <recipient_city>'.$orderInfo->shipping_city.'</recipient_city>
		        <recipient_addres>'.$buyer_address.'</recipient_addres>
		        <recipient_postcode>'.$orderInfo->shipping_zipcode.'</recipient_postcode>
		        <recipient_phone>'.$buyer_phone.'</recipient_phone>
		        <to_local></to_local>
		        <recipient_country_local></recipient_country_local>
		        <recipient_province_local></recipient_province_local>
		        <recipient_city_local></recipient_city_local>
		        <recipient_addres_local></recipient_addres_local>
		        <type_no>4</type_no>
		        <from_country>China</from_country>
		        <user_desc>'.$orderInfo->id.'</user_desc>
		        <content>'.$content.'</content>
		        <num>'.$total_count.'</num>
		        <weight>'.round($total_weight,3).'</weight>
		        <single_price>'.$total_value.'</single_price>
		        <trande_no>'.$wishID.'</trande_no>
		        <trade_amount>'.$total_value.'</trade_amount>
		        <receive_from>'.$this->senderAndLanShou[$shipInfo[1]]['cn']['username'].'</receive_from>
		        <receive_province>'.$this->senderAndLanShou[$shipInfo[1]]['cn']['province'].'</receive_province>
		        <receive_city>'.$this->senderAndLanShou[$shipInfo[1]]['cn']['city'].'</receive_city>
		        <receive_addres>'.$this->senderAndLanShou[$shipInfo[1]]['cn']['address'].'</receive_addres>
		        <receive_phone>'.$this->senderAndLanShou[$shipInfo[1]]['cn']['phone'].'</receive_phone>
		        <warehouse_code>'.$this->warehouseArr[$shipInfo[2]].'</warehouse_code>
		        <doorpickup>1</doorpickup>
		      </order>
		    ';
		 $xmlStr .='</orders>';
	 return $xmlStr; 
	}
	/**
	 * Curl http Post 数
	 * 使用方法：
	 * $post_string = "app=request&version=beta";
	 * postCurlData('http://www.test.cn/restServer.php',$post_string);
	 */
	public function postCurlData($remote_server, $post_string) {
		$ch = curl_init(); 
		$header[] = "Content-type: text/xml";//定义content-type为xml 
		curl_setopt($ch, CURLOPT_URL, $remote_server); //定义表单提交地址 
		curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET 
		curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string); //定义提交的数据，这里是XML文件 
		$tmpInfo = curl_exec ( $ch ); // 执行操作

		 curl_close($ch);//关闭
		 return $tmpInfo;
	}
	
}