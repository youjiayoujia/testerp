<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-08
 * Time: 16:13
 */
namespace App\Modules\Logistics\Adapter;


class ShunfenghlAdapter extends BasicAdapter
{


    public function __construct($config)
    {

        $this->_input_code = $config['userId'];
        $this->_check_code = $config['key'];
        $this->_url = $config['url'];
        $this->_sendstarttime = '';
        $this->sendInfo['j_company'] = $config['returnCompany'];
        $this->sendInfo['j_contact'] = $config['returnContact'];
        $this->sendInfo['j_tel'] = $config['returnPhone'];
        $this->sendInfo['j_address'] = $config['returnAddress'];
        $this->sendInfo['j_province'] = $config['returnProvince'];
        $this->sendInfo['j_city'] = $config['returnCity'];
        $this->sendInfo['j_post_code'] = $config['returnZipcode'];
        $this->sendInfo['j_country'] = $config['returnCountry'];
        $this->sendInfo['custid'] = '';
        $this->_express_type =!empty($config['type'])?$config['type']:'A1';
        $this->SoapClient =  new  \SoapClient($this->_url);

    }

    public function getTracking($package)
    {
        $response = $this->doUpload($package);
        if ($response['status'] != 0) {
            $shiping_code=isset($response['msg']->Body->OrderResponse['mailno'])?$response['msg']->Body->OrderResponse['mailno']:'';//顺丰运单号
            $agent_code=isset($response['msg']->Body->OrderResponse['agent_mailno'])?$response['msg']->Body->OrderResponse['agent_mailno']:'';//服务商号
            if(empty($agent_code)){
                $agent_code = $shiping_code;
            }
            $result = [
                'code' => 'success',
                'result' =>$agent_code, //跟踪号
                'result_other' => $shiping_code
            ];
        }else{
            $result =[
                'code' => 'error',
                'result' => $response['msg']
            ];
        }

        return $result;
    }

    public function doUpload($package)
    {
        $return  = [];
        $count = 0;
        $amount = 0;
        $proStr ='';
        foreach ($package->items as $key => $item) {
            $count = $count+$item->quantity;
            $amount = $amount +$item->item->product->declared_value;
            $products_declared_en = $item->item->product->declared_en;
        }

        $amount = $amount>20?20:$amount;
        $proStr.='<Cargo ename="'.$products_declared_en.'" count="'.$count.'"  weight="'.$package->weight.'" amount="'.$amount.'" ></Cargo>';

        $package->shipping_state = empty( $package->shipping_state)?$package->shipping_city:$package->shipping_state;
        $xmlStr ='';
        $xmlStr .= '<?xml version="1.0" encoding="UTF-8"?>
		   		 <Request service="OrderService" lang="zh_CN">';
        $xmlStr .=' <Head>'.$this->_input_code.'</Head>';
        $xmlStr .= '<Body>
			       <Order
			        orderid="V3LME'.$package->id.'" express_type="'.$this->_express_type.'"
			        j_company="'.$this->sendInfo['j_company'].'" j_contact="'.$this->sendInfo['j_contact'].'" j_tel="'.$this->sendInfo['j_tel'].'" j_mobile="'.$this->sendInfo['j_tel'].'" j_address="'.$this->sendInfo['j_address'].'"
			        d_contact="'.htmlspecialchars($package->shipping_firstname.' '.$package->shipping_lastname).'" d_tel="'.$package->shipping_phone.'" d_mobile="'.$package->shipping_phone.'" d_address="'.htmlspecialchars($package->shipping_address).($package->shipping_address1 ? ' '.htmlspecialchars($package->shipping_address1) : '').'"
			        parcel_quantity="1" j_province="'.$this->sendInfo['j_province'].'" j_city="'.$this->sendInfo['j_city'].'" d_province="'.$package->shipping_state.'" d_city="'.$package->shipping_city.'"
			        operate_flag="1" j_country="'.$this->sendInfo['j_country'].'" j_post_code="'.$this->sendInfo['j_post_code'].'" d_country="'.$package->shipping_country.'" d_post_code="'.$package->shipping_zipcode.'"
			        cargo_total_weight="'.$package->weight.'" returnsign="Y"
			       > ';
        $xmlStr.= $proStr;
        $xmlStr.='
		       </Order>
		     </Body>
		    </Request>';
        $code=strtoupper(md5($xmlStr.$this->_check_code));
        $verifyCode=base64_encode($code);
        $call=$this->SoapClient->sfexpressService($xmlStr,$verifyCode);
        $result=simplexml_load_string($call);
        if($result->Head=='OK'){
            //获取追踪号成功，直接确认订单信息
            $return['status'] = 1;
            $return['msg'] = $result;

        }elseif($result->Head=='ERR'){
            $return['status'] = 0;
            $return['msg'] = $result->ERROR['code'];
        }

        return $return;
    }
}