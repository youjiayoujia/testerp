<?php
/** 线上Eub
 * Created by PhpStorm.
 * User: lidabiao
 * Date: 2016-12-12
 * Time: 13:25
 */

namespace App\Modules\Logistics\Adapter;

use App\Models\Channel\AccountModel;
use App\Models\Order\ItemModel;
use App\Models\Logistics\SupplierModel;
use App\Models\PackageModel;
use Illuminate\Support\Facades\Storage;
class DhlAdapter extends BasicAdapter
{
    public function __construct($config)
    {
        //$this->_authenticate = $config['key'];
        //$this->_customer_code = $config['userId'];
        //$this->_vip_code = $config['userPassword'];

        ////////////正式环境////////////////////////////
        $this->GetShipHost='https://api.dhlecommerce.asia/rest/v2/Label';//获取追踪号地址
        $this->CheckOutHost='https://api.dhlecommerce.asia/rest/v2/Order/Shipment/CloseOut';//确认发货地址
        $this->getTokenUrl = "https://api.dhlecommerce.asia/rest/v1/OAuth/AccessToken?returnFormat=json";//获取TOKEN地址
        $this->account = '5243380896';//账号

        ////////////////////////////////////////测试环境数据/////////////////////////////////////
//		$this->GetShipHost='https://apitest.dhlecommerce.asia/rest/v2/Label';//获取追踪号地址
//		$this->CheckOutHost='https://apitest.dhlecommerce.asia/rest/v2/Order/Shipment/CloseOut';//确认发货地址
//		$this->getTokenUrl = "https://apitest.dhlecommerce.asia/rest/v1/OAuth/AccessToken?returnFormat=json";//获取TOKEN地址
//        $this->account = '520285';//账号

        $this->qz = 'CNAMMERP3';//物流号前缀
        $this->_express_type =!empty($config['type'])?$config['type']:'PKD';
        $this->get_olderp_token =1;
    }
    public function checkToken($url){
        $result = $this->getCurlHttpsData($url);
        $result = json_decode($result);
        @$status = $result->accessTokenResponse->responseStatus->code;
        @$token = $result->accessTokenResponse->token;
        if($status == '100000' && $token){
            //获取token成功
            $this->token = $token;
        }
    }
    public function maketoken($orderInfo){
        //customer_id存储格式:账号,api账号,时间
        $customer_id = $orderInfo->logistics->supplier->customer_id;
        $model = SupplierModel::find($orderInfo->logistics->supplier->id);
        $this->token = $orderInfo->logistics->supplier->secret_key;

        $customer_id = explode(',',$customer_id);
        $this->account=$customer_id[0];//账号
        $clientId=$customer_id[1];//API账号
        $gqtime=@$customer_id[2]?$customer_id[2]:0;//过期时间
        $lasttime = time()-24*60*60;
        if($lasttime > $gqtime){
            //暂时关掉自动更新
            $result =[
                'code' => 'error',
                'result' => 'TOKEN过期,暂时请到V1手动更新过了'
            ];
            return $result;

            //token过期
            $password = $orderInfo->logistics->supplier->password;//API密码
            if(!$clientId || !$password){
                $result =[
                    'code' => 'error',
                    'result' => '供应商账号密码错误'
                ];
                return $result;
            }
            $url = $this->getTokenUrl.'&clientId='.$clientId.'&password='.$password;
            $this->checkToken($url);
            if(!$this->token){
                $result =[
                    'code' => 'error',
                    'result' => '获取token失败'
                ];
                return $result;
            }else{
                $customer_id[2] = time();
                $customer_id = implode(',',$customer_id);
                $res = $model->update(['customer_id' => $customer_id,'secret_key'=>$this->token]);
            }

        }
    }
    public function getTracking($orderInfo){

        //Token获取，验证和更新
        $result = $this->maketoken($orderInfo);
       if($result['code'] =='error'){
           return $result;
       }
        if($orderInfo->warehouse_id =1){
            //深圳仓
            $this->sendInfo = array(
                'j_company' => 'SALAMOER',                 //寄件人公司
                'j_contact' => 'huangchaoyun',                     //寄件人
                'j_tel' => '18038094536',                  //电话
                'j_address1' => 'B3-4 Hekan Industrial Zone, No.41', //地址
                'j_address2' => 'No.41',
                'j_address3' =>' Wuhe Road South LONGGANG',
                'j_province' => 'GUANGDONG',                    //省
                'j_city' => 'SHENZHEN',                        //市
                'j_post_code' => '518129',                 //邮编
                'j_country' => 'CN',                       //国家
                'custid' => '7555769565'
            );
        }else{
            //义乌仓
            $this->sendInfo=array(
                'j_company' => 'JINHUA MOONAR',                 //寄件人公司
                'j_contact' => 'xiehongjun',                     //寄件人
                'j_tel' => '15024520515',                  //电话
                'j_address1' => 'Buliding 1-4, Jinyi Postal Park, No.2011',//地址
                'j_address2' => 'No.2011',//地址
                'j_address3' =>'JinGangDaDao West, JINDONG',
                'j_province' => 'ZHEJIANG',                    //省
                'j_city' => 'JINHUA',                        //市
                'j_post_code' => '321000',                 //邮编
                'j_country' => 'CN',                       //国家
                'custid' => '5796625949'
            );
        }

        $totalWeight = 0;
        $totalValue =0;
        $proStr = '';
        $hscode='1111111111';
        foreach($orderInfo->items as $key => $item){
//            if(!$v['products_declared_en']){
//                $res = array('status'=>false,'info'=>'SKU缺少申报英文名');
//                return $res;
//            }
            $totalWeight += $item->quantity * $item->item->weight;
            $totalValue += $item->quantity * $item->item->product->declared_value;
            $thePro = $item->quantity * $item->item->weight;
            $thePro = $thePro *1000;
            $item->item->product->declared_value = sprintf("%.2f",$item->item->product->declared_value);
            $products_declared_en = $item->item->product->declared_en?$item->item->product->declared_en:'Dress';
            //产品字符串

            $proStr.='{
					 "skuNumber": "'.$item->item->product->model.'",
					 "description": "'.$products_declared_en.'",
					 "descriptionImport": null,
					 "descriptionExport": "连衣裙",
					 "itemValue": '.$item->item->product->declared_value.',
					 "itemQuantity": '.$item->quantity.',
					 "grossWeight": '.$thePro.',
					 "contentIndicator": null,
					 "countryOfOrigin": "CN",
					 "hsCode": "'.$hscode.'"
					 },';
        }
        $proStr = trim($proStr,',');
        $totalValue = sprintf("%.2f",$totalValue);
        $totalWeight = $totalWeight * 1000;
        //创建获取追踪号发送的数据
        $dateTime = date("Y-m-d").'T'.date('H:i:s').'+08:00';
        $orderInfo->shipping_address1 = $orderInfo->shipping_address1?$orderInfo->shipping_address1:'null';
        $orderInfo->shipping_address2 = $orderInfo->shipping_address2?$orderInfo->shipping_address2:'null';
        $orderInfo->shipping_state = $orderInfo->shipping_state?$orderInfo->shipping_state:'null';
        $orderInfo->shipping_country = $orderInfo->shipping_country ?$orderInfo->shipping_country :'null';
        $orderInfo->currency_type = $orderInfo->currency_type?$orderInfo->currency_type:'USD';
        $qddm = $this->_express_type;
        if($qddm == 'PKD' || $qddm == 'PPS' || $qddm == 'PLT'){
            $incoterm = 'DDU';
            $hscode='null';
        }elseif($qddm == 'PLE'){
            $incoterm = 'DDP';
            if($orderInfo->shipping_state){
                //此渠道用简称
                $orderInfo->shipping_state=$this->getUsaAp(trim($orderInfo->shipping_state));
            }

        }else{
            $res = array('status'=>'error','info'=>'渠道代码错误,或没开通:'.$qddm);
            return $res;
        }
        $orderInfo->shipping_phone = (int)$orderInfo->shipping_phone?$orderInfo->shipping_phone:'1111111';
        $shipmentID = $this->qz.$orderInfo->id;
        if(!trim($orderInfo->shipping_state)){
            $res = array('status'=>'error','info'=>'发货地址缺少省/州');
            return $res;
        }elseif(!trim($orderInfo->shipping_city)){
            $res = array('status'=>'error','info'=>'发货地址缺少城市');
            return $res;
        }elseif(!trim($orderInfo->shipping_zipcode)){
            $res = array('status'=>'error','info'=>'发货地址缺少邮编');
            return $res;
        }
        $data='{
				 "labelRequest": {
				 "hdr": {
				 "messageType": "LABEL",
				 "messageDateTime": "'.$dateTime.'",
				 "accessToken": "'.$this->token.'",
				 "messageVersion": "1.2",
				 "messageLanguage": "zh_CN"
				 },
				 "bd": {
				 "pickupAccountId": "'.$this->account.'",
				 "soldToAccountId": "'.$this->account.'",
				 "pickupDateTime": "'.$dateTime.'",
				 "pickupAddress": {
				 "companyName": "SLME",
				 "name": "SELLMORE (HK) TRADE CO., Ltd",
				 "address1": "'.$orderInfo->shipping_address.'",
				 "address2": "'.$orderInfo->shipping_address1.'",
				 "address3": "'.$orderInfo->shipping_address2.'",
				 "city": "'.$this->sendInfo['j_city'].'",
				 "state": "'.$this->sendInfo['j_province'].'",
				 "district": null,
				 "country": "'.$this->sendInfo['j_country'].'",
				 "postCode": "'.$this->sendInfo['j_post_code'].'",
				 "phone": "'.$this->sendInfo['j_tel'].'",
				 "email": null
				 },
				 "shipperAddress": {
				 "companyName": "SLME",
				 "name": "SELLMORE (HK) TRADE CO., Ltd",
				 "address1": "'.$this->sendInfo['j_address1'].'", 
				 "address2": "'.$this->sendInfo['j_address2'].'",
				 "address3": "'.$this->sendInfo['j_address3'].'",
				 "city": "'.$this->sendInfo['j_city'].'",
				 "state": null,
				 "district": null,
				 "country": "'.$this->sendInfo['j_country'].'",
				 "postCode": null,
				 "phone": null,
				 "email": null
				 },
				 "shipmentItems": [
				 {
				 "consigneeAddress": {
				 "companyName": null,
				 "name": "'.$orderInfo->shipping_firstname.' '.$orderInfo->shipping_lastname.'",
				 "address1": "'.$orderInfo->shipping_address.'",
				 "address2": "'.$orderInfo->shipping_address1.'",
				 "address3": "'.$orderInfo->shipping_address2.'",
				 "city": "'.$orderInfo->shipping_city.'",
				 "state": "'.$orderInfo->shipping_state.'",
				 "district": null,
				 "country": "'.$orderInfo->shipping_country.'",
				 "postCode": "'.$orderInfo->shipping_zipcode.'",
				 "phone": "'.$orderInfo->shipping_phone.'",
				 "email": null,
				 "idNumber": null,
				 "idType": null
				 },
				 "returnAddress": {
				 "companyName": "SLME",
				 "name": "SELLMORE (HK) TRADE CO., Ltd",
				 "address1": "'.$this->sendInfo['j_address1'].'",
				 "address2": "'.$this->sendInfo['j_address2'].'",
				 "address3": "'.$this->sendInfo['j_address3'].'",
				 "city": "'.$this->sendInfo['j_city'].'",
				 "state": null,
				 "district": null,
				 "country": "'.$this->sendInfo['j_country'].'",
				 "postCode": null,
				 "phone": null,
				 "email": null
				 },
				 "shipmentID": "'.$shipmentID.'",
				 "deliveryConfirmationNo": null,
				 "packageDesc": "'.$products_declared_en.'",
				 "totalWeight": '.$totalWeight.',
				 "totalWeightUOM": "G",
				 "dimensionUOM": null,
				 "height": null,
				 "length": null,
				 "width": null,
				 "customerReference1": null,
				 "customerReference2": null,
				 "productCode": "'.$qddm.'",
				 "incoterm": "'.$incoterm.'",
				 "contentIndicator": null,
				 "codValue": 0.00,
				 "insuranceValue": null,
				 "freightCharge": 0.00,
				 "totalValue": '.$totalValue.',
				 "currency": "'.$orderInfo->currency_type.'",
				 "shipmentContents": [
				'.$proStr.'
				 ]
				 }
				 ],
				 "label": {
				 "pageSize": "400x400",
				 "format": "PNG",
				 "layout": "1x1"
				 }
				 }
				 }
				}';
        $data = str_replace("\\","",$data);
        $data = str_replace("\r\n","",$data);
        $data_obj = json_decode($data);
        if(!$data_obj){
            $result = [
                'code' => 'error',
                'result' =>'数据组装出现错误，请联系IT'
            ];
            return $result;
        }
        $url = $this->GetShipHost;
        $result = $this->postCurlHttpsData($url,$data);echo "<pre/>";var_dump($result);
        @$result = json_decode($result);
        @$status = $result->labelResponse->bd->responseStatus->code;//200时为成功
        if($status == '200'){
            $shipmentID = $result->labelResponse->bd->labels[0]->shipmentID;

            //保存图片面单
            $shipmentArray = $result->labelResponse->bd->labels;//返回图片组,可能有多张图片
            $num = 0;
            foreach($shipmentArray as $v){
                $status = $v->responseStatus->code;
                if($status == '200' || $status == '203'){
                    //获取二进制流图片成功，生成图片
                    $shipmentImg = $v->content;//面单二进制流
                    $shipmentImg=base64_decode($shipmentImg);
                    $filename = 'dhl_'.$orderInfo->id.'_'.$num;
                    $uploads_file ='/dhl_md/md/'.$filename.'.jpg';
                    Storage::put($uploads_file,$shipmentImg);
                    $num++;
                }
            }
            @$model = PackageModel::where('id',$orderInfo->id);
            @$model->update(['sure_tracking_no'=>0]);
            $result = [
                'code' => 'success',
                'result' =>$shipmentID //跟踪号
            ];
        }else{
            if(@$result->labelResponse->bd->labels[0]->responseStatus->messageDetails){
                $msg =$result->labelResponse->bd->labels[0]->responseStatus->messageDetails;
            }else{
                @$msg =  $result->labelResponse->bd->responseStatus->messageDetails;
            }
            if(@$msg){
                $res = array('status'=>false,'info'=>'请求信息失败:'.$msg);
                $result =[
                    'code' => 'error',
                    'result' => '请求信息失败:'.$msg
                ];
            }else{
                $result =[
                    'code' => 'error',
                    'result' => '获取追踪号失败'
                ];
            }
        if($status==202 && $this->get_olderp_token ==1){
            $orderInfo=$this->getolderpToken($orderInfo);
            $this->get_olderp_token=2;
            $result=$this->getTracking($orderInfo);
        }
        }
        return $result;
    }
    public function getolderpToken($orderInfo){
        $customer_id = $orderInfo->logistics->supplier->customer_id;
        $model = SupplierModel::find($orderInfo->logistics->supplier->id);
        $url = "http://erp.moonarstore.com/api/get_dhl_token.php";
        $res = $this->getCurlHttpsData($url);
        $res = @explode(',',$res);
        if(@$res[1]){
            $this->token=$orderInfo->logistics->supplier->secret_key=$res[0];
            $customer_id = explode(',',$customer_id);
            $customer_id[2] = $res[1];
            $customer_id = implode(',',$customer_id);
            //$res = $model->update(['customer_id' => $customer_id,'secret_key'=>$this->token]);
            $res = $model->update(['secret_key'=>$this->token]);
        }
        return $orderInfo;
    }
    //创建确认订单的发送数据
    public function createSureShip($orderArray){
        $dateTime = date("Y-m-d").'T'.date('H:i:s').'+08:00';
        $orderStr = '';
        foreach($orderArray as $v){
            $orderStr.='{"shipmentID": "'.$v->tracking_no.'"},';
        }

        $orderStr = trim($orderStr,',');
        //echo "<pre/>";var_dump($orderStr);exit;
        $datacheckout='
			{
			 "closeOutRequest": {
			 "hdr": {
			 "messageType": "CLOSEOUT",
			 "accessToken":"'.$this->token.'",
			 "messageDateTime": "'.$dateTime.'",
			 "messageVersion": "1.2",
			 "messageLanguage": "zh_CN"
			 },
			 "bd": {
			 "customerAccountId": null,
			 "pickupAccountId": "'.$this->account.'",
			 "soldToAccountId": "'.$this->account.'",
			 "handoverMethod": 2,
			 "handoverID": null,
			 "shipmentItems": [
			 '.$orderStr.'
			 ]
			 }
			 }
			} 	';
        return $datacheckout;
    }
    public function SendSureOrderShip($orderArray)
    {
        //Token获取，验证和更新CNAMMERP362222
        if(@!$this->token){
           //echo "<pre/>";var_dump($orderArray[0]->logistics->supplier->customer_id);exit;
            $result = $this->maketoken($orderArray[0]);
            if($result['code'] =='error'){
                return $result;
            }
        }
        $url = $this->CheckOutHost;

        $data = $this->createSureShip($orderArray);


        $result = $this->postCurlHttpsData($url, $data);

        $result = json_decode($result);
        $status = $result->closeOutResponse->bd->responseStatus->code;//200时为成功
        @$shipmentArray = $result->closeOutResponse->bd->shipmentItems;
        if($status == '200'){
            //确认发货成功
            foreach($shipmentArray as $v){
                $orderStatus =  $v->responseStatus->code;
                if($orderStatus == '200'){
                    $orderId = preg_replace("/".$this->qz."/", "", $v->shipmentID);
                    $model = PackageModel::where('id',$orderId);
                    $model->update(['sure_tracking_no'=>1]);
                }
            }
            //生成PDF运单文件
            $shipmentImg = $result->closeOutResponse->bd->handoverNote;
            $handoverID = $result->closeOutResponse->bd->handoverID;
            $shipmentImg=base64_decode($shipmentImg);
            $type = 'pdf';
            $uploads_file ='/dhl_md/checkOut/'.$handoverID.'.'.$type;
            Storage::put($uploads_file,$shipmentImg);
            $res = array('status'=>true,'info'=>'此批次确定发货成功');
            return $res;
        }else{
            $newOrderArray = array();
            if($shipmentArray){
                foreach($shipmentArray as $v){
                    $code = $v->responseStatus->code;
                    $orderId = preg_replace("/".$this->qz."/", "", $v->shipmentID);

                    if($code == '201'){
                        $newOrderArray[] = array(
                            'erp_orders_id'=>$orderId,
                            'orders_shipping_code'=>$v->shipmentID
                        );
                    }elseif($code == '202'){
                        //已被确认发货成功
                        foreach($shipmentArray as $v){
                            $orderId = preg_replace("/".$this->qz."/", "", $v->shipmentID);
                            $model = PackageModel::where('id',$orderId);
                            $model->update(['sure_tracking_no'=>1]);
                        }
                    }else{
                        //失败
                    }
                }
            }

            if(count($newOrderArray) > 0){
                $result = $this->SendSureOrderShip($newOrderArray);
                exit;
            }
            $msg = $result->closeOutResponse->bd->responseStatus->messageDetails;
            $res = array('status'=>false,'info'=>'此批次确定发货失败,code:'.$status.',错误信息:'.$msg);
            return $res;
        }

    }
    public function getCurlHttpsData($url) { // 模拟提交数据函数
        $headers = array(
            'Content-Type: application/json'
        );
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
       // curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
        //curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        curl_setopt ( $curl, CURLOPT_POST, 0 ); // 发送一个常规的Post请求
        //curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
        curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec ( $curl ); // 执行操作
        if (curl_errno ( $curl )) {
            die(curl_error ( $curl )); //异常错误
        }
        curl_close ( $curl ); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
    public function postCurlHttpsData($url, $data) { // 模拟提交数据函数
        $headers = array(
            'Content-Type: application/json'
        );
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
       // curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
        //curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
        curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec ( $curl ); // 执行操作
        if (curl_errno ( $curl )) {
            die(curl_error ( $curl )); //异常错误
        }
        curl_close ( $curl ); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}