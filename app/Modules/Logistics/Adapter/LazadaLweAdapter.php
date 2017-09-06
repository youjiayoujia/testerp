<?php
/**  Lazada物流下单
 * Created by PhpStorm.
 * User: lidabiao
 * Date: 2016-08-18
 */
namespace App\Modules\Logistics\Adapter;
use App\Models\Channel\AccountModel;
use App\Models\Order\ItemModel;

class LazadaLweAdapter extends BasicAdapter{

    public function getTracking($package)
    {
        //$package = PackageModel::findOrFail(1000);
        $this->config = $package->logistics->api_config;
        $config = $this->config;
        $url = $config['url'];
        $orderid = $package->order_id;
        //包裹对应的账号信息
        $account = AccountModel::find($package->channel_account_id);
        $api_host = $account->lazada_api_host;
        $Key = $account->lazada_access_key;
        $lazada_user_id = $account->lazada_user_id;
        $logistics_name = $package->logistics->logistics_code;//物流编码
        /*物流编码
        AS-4PX-Postal-Singpost
        AS-china-post
        AS-LWE-Postal-MY Post-API SC
        AS-LWE-Postal-MY Post
        LGS-LEX-ID
        AS-LBC-JZ-express premium-JZ2-API SC
        AS-LBC-JZ-express-JZ-API SC
        AS-Poslaju
        LGS-PH1
        LGS-SG1
        LGS-SG2
        LGS-TH1
        LGS-SG3*/
        if(!$logistics_name){
            $result = [
                'code' => 'error',
                'result' => '物流编码为空.'
            ];
            return $result;
        }

        //上传空的追踪号，标记准备发货，生成追踪号
        $items = $package->items;
        $OrderItemIds = array();
        foreach($items as $key=>$v){
            $order_item_id = $v->order_item_id;
            $order_item = ItemModel::find($order_item_id);
            $orders_item_number = $order_item->orders_item_number;
            $OrderItemIds[]= $orders_item_number;
        }
        if(!$OrderItemIds){
            $result = [
                'code' => 'error',
                'result' => '包裹商品orders_item_number为空.'
            ];
            return $result;
        }
        $OrderItemIds = array_unique($OrderItemIds);
        $OrderItemIds = implode(',',$OrderItemIds );
        $relut = $this->upLoadShippingCode($api_host, $Key, $lazada_user_id,'', $OrderItemIds, $logistics_name);
        $re = $this->XmlToArray($relut);
       // echo "<pre/>";var_dump($re);exit;
        if (isset($re['Body']['OrderItems']['OrderItem'])) {
            //上传空追踪号成功
            //获取追踪号
             $Xml_data = $this->getshippingcode($token['api_host'], $token['Key'], $token['lazada_user_id'], $orderid);
             $orders_data = $this->XmlToArray($Xml_data);
            if (isset($orders_data['Body']['Orders']['Order'])) {//获取成功

                $last_orders_data = $orders_data['Body']['Orders']['Order'];

                $need_get_shippingcode_array = array_unique($need_get_shippingcode_array);

                $count_order = count($need_get_shippingcode_array);//传递的外单号的个数，1个和多个数据结构不一样
                //1个数据的时候
                $ordersShippingCode = $this->tabledeal($last_orders_data);
                if($ordersShippingCode){
                    //ok
                    $result = [
                        'code' => 'success',
                        'result' => $ordersShippingCode
                    ];
                }else{
                    $result = [
                        'code' => 'error',
                        'result' => '上传追踪号成功，获取失败!'
                    ];
                }
                return $result;
            }

        } else {
            $op = $v['erp_orders_id'] . "上传追踪号失败，信息为 " . $re['Head']['ErrorMessage'];
            $result = [
                'code' => 'error',
                'result' => $op
            ];
            return $result;
        }

    }
    //把插入表的操作封装成方法，方便调用
    public function tabledeal($order){
        $pagenumber = array();

        $order_shipping_code = array();
        if(isset($order['OrderItems']['OrderItem'][0])) //多SKU的
        {

            foreach ($order['OrderItems']['OrderItem'] as $items)//循环订单产品
            {

                if(!empty($items['PackageId']))
                {
                    $pagenumber[$order['OrderNumber']][$items['OrderItemId']] = $items['PackageId'];
                }
                if(!empty($items['TrackingCode']))
                {
                    $order_shipping_code[$order['OrderNumber']][$items['OrderItemId']] = $items['TrackingCode'];
                }

            }
        }
        else
        {

            $pagenumber[$order['OrderNumber']][$order['OrderItems']['OrderItem']['OrderItemId']]=$order['OrderItems']['OrderItem']['PackageId'];
            $order_shipping_code[$order['OrderNumber']][$order['OrderItems']['OrderItem']['OrderItemId']]=$order['OrderItems']['OrderItem']['TrackingCode'];
        }
        foreach($pagenumber as $key => $v){
            foreach($v as $k => $va){
                $ordersShippingCode = $order_shipping_code[$key][$k];
                //返回追踪号
                return $ordersShippingCode;

            }

        }
    }
    //上传追踪号，进行标记发货
    public function upLoadShippingCode($api_host,$token,$user,$TrackNumber,$OrderItemIds,$ShippingProvider)
    {
        $now = new \DateTime();

        $parameters = array(
            'UserID' => $user,
            'Action' => 'SetStatusToReadyToShip',
            'OrderItemIds'=>'['.$OrderItemIds.']',
            'DeliveryType' => 'dropship',
            'ShippingProvider' =>$ShippingProvider ,
            'TrackingNumber' => $TrackNumber,
            'Timestamp' => $now->format(\DateTime::ISO8601),
            //  'UserID' => 'lazada.api@moonar.com',
            'Version' => '1.0',

        );
        ksort($parameters);
        $params = array();

        foreach ($parameters as $name => $value) {

            $params[] = rawurlencode($name) . '=' . rawurlencode($value);

        }
        $strToSign = implode('&', $params);

        $parameters['Signature'] = rawurlencode(hash_hmac('sha256', $strToSign, $token, false));

        $request = http_build_query($parameters);

        $info =$this->getCurlData($api_host.'/?'.$request);

        return $info;
    }
    //获取追踪号
    public function getshippingcode($api_host,$token,$user,$OrderIdList)
    {
        $now = new DateTime();

        $parameters = array(
            'UserID' => $user,
            'Action' => 'GetMultipleOrderItems',
            'OrderIdList'=>'['.$OrderIdList.']',
            'Timestamp' => $now->format(DateTime::ISO8601),
            'Version' => '1.0',

        );
        ksort($parameters);
        $params = array();

        foreach ($parameters as $name => $value) {

            $params[] = rawurlencode($name) . '=' . rawurlencode($value);

        }
        $strToSign = implode('&', $params);

        $parameters['Signature'] = rawurlencode(hash_hmac('sha256', $strToSign, $token, false));

        $request = http_build_query($parameters);

        $info =$this->getCurlData($api_host.'/?'.$request);


        return $info;
    }
    //通过curl会话发送API请求获取数据
    public function getCurlData($queryString)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $queryString);

        curl_setopt($curl, CURLOPT_HEADER, 0);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $content = curl_exec($curl);

        curl_close($curl);

        return $content;
    }
    public function XmlToArray($xml)
    {
        $array = (array)(simplexml_load_string($xml));
        foreach ($array as $key => $item) {

            $array[$key] = $this->struct_to_array((array)$item);
        }
        return $array;
    }
    public function struct_to_array($item)
    {
        if (!is_string($item)) {

            $item = (array)$item;
            foreach ($item as $key => $val) {

                $item[$key] = $this->struct_to_array($val);
            }
        }
        return $item;
    }
}
