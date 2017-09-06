<?php
/** 线下eub
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-09
 * Time: 14:42
 */

namespace App\Modules\Logistics\Adapter;

use App\Models\CountriesModel;

class EubofflineAdapter extends BasicAdapter
{


    public function __construct($config)
    {
        //$this->_authenticate = $config['key'];
        //$this->_customer_code = $config['userId'];
        //$this->_vip_code = $config['userPassword'];
        $this->_version = 'international_eub_us_1.1';
        $this->_url ='http://shipping.ems.com.cn/partner/api/public/p/order/';//发送地址
    }


    public function getTracking($package)
    {
        $emailTemplateInfo = $package->logistics->emailTemplate;
        $apiInfoArr=explode(',',$emailTemplateInfo->eub_api);
        $this->_authenticate = $apiInfoArr[0];//授权码
        $this->_customer_code = $apiInfoArr[1];//客户编码
        $this->_vip_code = '';  //大客户编码;
        if(array_key_exists('2', $apiInfoArr)){
            $this->_vip_code = $apiInfoArr[2];
        }         
        //寄件人信息
        $this->_sender = $emailTemplateInfo->eub_sender;
        $this->_senderZip = $emailTemplateInfo->eub_sender_zipcode;
        $this->_senderMobile = $emailTemplateInfo->eub_phone;
        $this->_senderProvinceCode = $emailTemplateInfo->eub_sender_province_code;
        $this->_senderCityCode = $emailTemplateInfo->eub_sender_city_code;
        $this->_senderAreaCode = $emailTemplateInfo->eub_sender_zone_code;
        $this->_senderCompany =$emailTemplateInfo->eub_sender_company;
        $this->_senderStreet = $emailTemplateInfo->eub_sender_street.$emailTemplateInfo->eub_sender_zone.$emailTemplateInfo->eub_sender_city.$emailTemplateInfo->eub_sender_province;
        $this->_senderEmail =$emailTemplateInfo->eub_sender_email;

        //揽收人信息
        $this->_contacter = $emailTemplateInfo->eub_contact_name;
        $this->_zipCode = $emailTemplateInfo->eub_zipcode;
        $this->_phone = $emailTemplateInfo->eub_phone;
        $this->_mobilePhone = $emailTemplateInfo->eub_mobile_phone;
        $this->_provinceCode = $emailTemplateInfo->eub_province_code;
        $this->_cityCode = $emailTemplateInfo->eub_city_code;
        $this->_areaCode = $emailTemplateInfo->eub_zone_code;
        $this->_company = $emailTemplateInfo->eub_contact_company_name;
        $this->_street = $emailTemplateInfo->eub_street;
        $this->_email = $emailTemplateInfo->eub_email;
    
        $response = $this->doUpload($package);
        print_r($response);
        if ($response['status'] != 0) {
            $result = [
                'code' => 'success',
                'result' => $response['msg']
            ];
        } else {
            $result = [
                'code' => 'error',
                'result' =>$response['msg']
            ];
        }
        return $result;
    }


    public function doUpload($package)
    {
        $items = '';        
        foreach ($package->items as $key => $item) {
            $products_declared_en = $item->item->product->declared_en;//产品申报英文名称
            $products_declared_en = str_replace("'", " ", $products_declared_en);
            $products_declared_cn = $item->item->product->declared_cn;
            if (!$products_declared_en || !$products_declared_cn) {
                return array('status' => '0', 'msg' => $item->item->product->model . '缺少申报的中英文名');
            }
            //$weight = $item->quantity * $item->item->product->weight;
            $weight = $item->item->weight;
            $weight = number_format($weight, 3, '.', '');//保留三位小数   
            $delcarevalue = $item->first()->item->declared_value;
            $delcarevalue = number_format($delcarevalue, 2, '.', '');//保留两位小数           
        }
        $items .= '<item>
      				<cnname>' . $products_declared_cn . ' ' . $item->item->product->model . '*' . $item->quantity . '</cnname><enname>' . $products_declared_en . '</enname>
					<count>' . $item->quantity . '</count><weight>' . $weight . '</weight>
					<delcarevalue>' . $delcarevalue . '</delcarevalue><origin>CN</origin>
				</item>
      			';
        $street = htmlspecialchars($package->shipping_address . ' ' . $package->shipping_address1);//收件人街道
        $xmlStr = '';
        $xmlStr .= '<?xml version="1.0" encoding="UTF-8"?>
		   		<orders xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><order>';
        $xmlStr .= '<orderid>SLME' . $package->id . '</orderid><operationtype>0</operationtype><producttype>0</producttype>
	  			 <customercode>' . $this->_customer_code . '</customercode><vipcode>' . $this->_vip_code . '</vipcode><clcttype>1</clcttype>
	  			 <pod>false</pod><untread>Returned</untread>
	  			 <printcode>01</printcode>';
        //寄件人信息
        $xmlStr .= '<sender>
	  				<name>' . $this->_sender . '</name><postcode>' . $this->_senderZip . '</postcode><phone>' . $this->_senderMobile . '</phone><mobile>' . $this->_senderMobile . '</mobile>
	  				<country>CN</country><province>' . $this->_senderProvinceCode . '</province><city>' . $this->_senderCityCode . '</city><county>' . $this->_senderAreaCode . '</county>
	  				<company>' . $this->_senderCompany . '</company><street>' . $this->_senderStreet . '</street><email>' . $this->_senderEmail . '</email>
	  			</sender>';
        //收件人信息
        if (empty($package->shipping_city)) {
            $package->shipping_city = '.';
        }
        if (empty($package->shipping_state)) {
            $package->shipping_state = '.';
        }
        $country = CountriesModel::where('code', $package->shipping_country)->first()->name;
        if (empty($country)) {
            return array('status' => '0', 'msg' => '未找到对应国家的全称');
        }
        //处理国家如果是美国的订单 United States
        if ($country == 'UNITED STATES') {
            $country = 'UNITED STATES OF AMERICA';
        }
        $xmlStr .= '<receiver>
					<name>' . $package->shipping_firstname . ' ' . $package->shipping_lastname . '</name><postcode>' . $package->shipping_zipcode . '</postcode><phone>' . $package->shipping_phone . '</phone>
					<mobile>' . $package->shipping_phone . '</mobile><country>' . $country . '</country><province>' . $package->shipping_state . '</province>
					<city>' . $package->shipping_city . '</city><street>' . $street . '</street><email>' . $package->email . '</email>
				</receiver>
	  			';
        //揽收人信息
        $xmlStr .= '<collect>
					<name>' . $this->_contacter . '</name>
					<postcode>' . $this->_zipCode . '</postcode>
					<phone>' . $this->_phone . '</phone>
					<mobile>' . $this->_mobilePhone . '</mobile>
					<country>CN</country>
					<province>' . $this->_provinceCode . '</province>
					<city>' . $this->_cityCode . '</city>
					<county>' . $this->_areaCode . '</county>
					<company>' . $this->_company . '</company>
					<street>' . $this->_street . '</street>
					<email>' . $this->_email . '</email>
				</collect>
	  			';
        //运送物品详情
        $xmlStr .= '<items>' . $items . '</items>';

        $xmlStr .= '</order></orders>';

        $headers = array(     
            'Content-Type: text/xml; charset=UTF-8',
            'authenticate:' . $this->_authenticate,
            'version:' . $this->_version,            
        );

        $result = $this->sendHttpRequest($this->_url, $xmlStr, $headers);
        return $result;

    }

    public function sendHttpRequest($url, $requestBody, $headers)
    {

        $connection = curl_init();     
        curl_setopt($connection, CURLOPT_VERBOSE, 1);        
        curl_setopt($connection, CURLOPT_URL, $url);                //set the server we are using (could be Sandbox or Production server)       
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);        //stop CURL from verifying the peer's certificate
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);        
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);     //set the headers using the array of headers        
        curl_setopt($connection, CURLOPT_POST, 1);                  //set method as POST        
        curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody); //set the XML body of the request       
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);        //set it to return the transfer as a string from curl_exec
        curl_setopt($connection, CURLOPT_TIMEOUT, 200);       
        $data = curl_exec($connection);                             //Send the Request  
        /*echo "<pre>";
        $httpcode = curl_getinfo($connection);  
        print_r($httpcode);*/
        if (curl_errno($connection)) {            
            $return['status'] = 0;
            $return['msg'] = curl_error($connection);
            return $return;
        }
        curl_close($connection);
        $result = simplexml_load_string($data);
        if ($result->status == 'error') {
            $return['status'] = 0;
            $return['msg'] = isset($result->description) ? (string)$result->description : '未找到错误详情';
        } else {
            if (isset($result->mailnum) && !empty($result->mailnum)) {
                $return['status'] = 1;
                $return['msg'] = (string)$result->mailnum;
            } else {
                $return['status'] = 0;
                $return['msg'] = '未返回追踪号,或CURL失败';
            }
        }
        return $return;

    }

}
