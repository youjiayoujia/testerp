<?php
/** 顺友物流 适配器  注：暂未判断 粉末 液体 带电情况（待完善）
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-06
 * Time: 14:24
 */
namespace App\Modules\Logistics\Adapter;

class ShunyouAdapter extends BasicAdapter
{

    public function __construct($config)
    {
        $this->_apiDevUserToken = $config["userId"];
        $this->_apiLogUsertoken = $config["key"];
        $this->_shippingMethodCode = $config['type'];
        $this->_senderName = $config['returnContact'];
        $this->_senderFullAddress = $config['returnAddress'];
        $this->_senderPhone = $config['returnPhone'];
        $this->_senderPostCode = $config['returnZipcode'];
        $this->_url = $config['url'];
    }


    /** 物流下单
     * @param package $package
     * @return array
     */
    public function getTracking($package)
    {
        $response = $this->doUpload($package);
        if ($response['status'] != 0) {
            $re = json_decode($response['msg']);
            $first_result=$re->data->resultList;//采用的都已单一订单上传，只用判断一个订单，
            $result_data = $first_result[0];
            $result_status = $result_data->processStatus;
            if($result_status == 'success'){
                $result = [
                    'code' => 'success',
                    'result' =>$result_data->trackingNumber //跟踪号
                ];
            }else{
                $result = $result_data->errorList;
                $data = $result[0];
                $result = [
                    'code' => 'error',
                    'result' => 'errorCode:'.$data->errorCode.',errorMsg:'.$data->errorMsg
                ];
            }
        }else{
            $result =[
                'code' => 'error',
                'result' => 'curl failure.'
            ];
        }
        return $result;
    }

    /** 数据组装+请求
     * @param $package
     * @return array
     */
    public function doUpload($package){
        $last_data = [];
        $last_data['apiDevUserToken'] =$this->_apiDevUserToken;
        $last_data['apiLogUsertoken'] =$this->_apiLogUsertoken;


        $buyer_code = $package->shipping_country;
        if($buyer_code=='UK'){
            $buyer_code = 'GB';
        }
        if($buyer_code=='SRB'&&$package->order->channel_id==2){ //速卖通的SRB 要改成RS
            $buyer_code = 'RS';
        }

        $products_with_battery = 0;//包裹电池属性0：不含电池,1：含电池,2：纯电池
        $products_with_powder = 0;//包裹粉末或液体属性，0：不包含，1：包含
        $products_with_food = 0;//目前公司不做食品，如果需要以后开发

        $productList =[];
        foreach ($package->items as $key => $item) {
            $product =[];
            $product['productSku'] =$item->item->product->model;
            $product['declareEnName'] =$item->item->product->declared_en;
            $product['declareCnName'] =$item->item->product->declared_cn;
            $product['quantity'] =$item->quantity;
            $product['declarePrice'] =$item->item->product->declared_value;
            $productList[] = $product;
        }
        $packages =[];
        $packages['customerOrderNo'] =$package->id;
        $packages['shippingMethodCode'] =$this->_shippingMethodCode;
        $packages['packageSalesAmount'] =round($package->order->amount/count($productList),2);
        $packages['predictionWeight'] =$package->weight;
        $packages['recipientName'] =$package->shipping_firstname.' '.$package->shipping_lastname; //人名拼接下
        $packages['recipientCountryCode'] =$buyer_code;
        $packages['recipientPostCode'] =$package->shipping_zipcode;
        $packages['recipientState'] =$package->shipping_state;
        $packages['recipientCity'] =$package->shipping_city;
        $packages['recipientStreet'] =$package->shipping_address.' '.$package->shipping_address1; //地址拼接下
        $packages['recipientPhone'] =$package->shipping_phone;
        $packages['recipientEmail'] =$package->order->email;
        $packages['senderName'] =$this->_senderName;
        $packages['senderFullAddress'] = $this->_senderFullAddress;
        $packages['senderPhone'] =$this->_senderPhone;
        $packages['senderPostCode'] =$this->_senderPostCode;
        $packages['insuranceFlag'] =0;
        $packages['packageAttributes'] ='000'; //暂时设置 不带电 不是粉末 不是液体
        $packages['productList'] = $productList;

        $last_data['data']['packageList'][] = $packages;

        $headers = array('Content-Type: application/json');
        $result = $this->curlPost($this->_url, json_encode($last_data), $headers);
        return $result;
    }




}