<?php
/** 欧速通
 * Created by PhpStorm.
 * User: lidabiao
 * Date: 2016-12-31
 * Time: 09:55
 */

namespace App\Modules\Logistics\Adapter;

use App\Models\Channel\AccountModel;
use App\Models\Order\ItemModel;
use App\Models\Logistics\SupplierModel;
use App\Models\PackageModel;
use Illuminate\Support\Facades\Storage;
class OstAdapter extends BasicAdapter
{
    public function __construct($config){
        $this->url = !empty($config['url'])?$config['url']:'http://114.119.9.156:8012/Order.aspx';
        $this->token = !empty($config['key'])?$config['key']:'basic U2VsbG1vcmU6U2VsbG1vcmU4ODg=';
    }
    public function getTracking($orderInfo){

        $orderInfo->shipping_country='GB';
        if($orderInfo->shipping_country !='GB'){
            $result =[
                'code' => 'error',
                'result' => '欧速通渠道只发往国家GB'
            ];
            return $result;
        }
        $data = array();
        foreach($orderInfo->items as $key => $item){
            $data['Customs'][] = array(
                'Sku'=>$item->item->product->model,
                'ChineseContentDescription'=>$item->item->product->declared_cn?$item->item->product->declared_cn:'连衣裙',
                'EnglishContentDescription'=>$item->item->product->declared_en?$item->item->product->declared_en:'dress',
                'ItemCount'=>$item->quantity,
                'ItemValue'=>$item->item->product->declared_value,
                'Currency'=>'USD',
                'ItemWeight'=>$item->item->weight,
            );
        }
        @$orderInfo->shipping_address1 = $orderInfo->shipping_address1?$orderInfo->shipping_address1:'null';
        @$orderInfo->shipping_address2 = $orderInfo->shipping_address2?$orderInfo->shipping_address2:'null';
        $data['OrderNumber'] = $orderInfo->id;
        $data['RecipientName'] = $orderInfo->shipping_firstname.' '.$orderInfo->shipping_lastname;
        $data['RecipientAddress'] = $orderInfo->shipping_address.' '.$orderInfo->shipping_address1.' '.$orderInfo->shipping_address2;
        $data['RecipientZipCode'] = $orderInfo->shipping_zipcode;
        $data['RecipientCity'] = $orderInfo->shipping_city;
        $data['RecipientState'] = $orderInfo->shipping_state;
        $data['RecipientCountry'] = $orderInfo->shipping_country;
        $data['PhoneNumber'] = $orderInfo->shipping_phone;
        $data['WeightUom'] = 'KG';

        $data = json_encode($data);

        $url = $this->url;
        $token = base64_encode($this->token);
        $headers = array(
            'Content-Type:application/json;charset=utf-8',
            'Authorization:'.$this->token
        );
        $result = $this->postCurlHttpsData($url,$data,$headers);
        //$result = '{"ProductBarcode":"0B0437995000000000648","CreateTime":""}';
        $result = json_decode($result,true);
        if(@$result['ProductBarcode']){
            $shipmentID = $result['ProductBarcode'];
            //成功
            $res = [
                'code' => 'success',
                'result' =>$shipmentID //跟踪号
            ];
            return $res;
        }else{
            if(@$result['model'][0]){
                $error = $result['model'][0];
            }else{
                $error='上传欧速通获取追踪号失败';
            }
            $res =[
                'code' => 'error',
                'result' => $error
            ];
            return $res;
        }
    }
    public function postCurlHttpsData($url, $data,$headers) { // 模拟提交数据函数
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        //curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
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