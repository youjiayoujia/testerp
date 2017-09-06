<?php
namespace App\Modules\Logistics\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/8/1
 * Time: 下午13:31
 */
class CoeAdapter extends BasicAdapter
{
    public function getTracking($package)
    {

        $this->config = $package->logistics->api_config;

        $result = [
            'code' => 'error',
            'result' => 'Sorry, error'
        ];
		if($package->tracking_no){
			$result = [
                    'code' => 'error',
                    'result' => 'Sorry, tracking numbers already exist'
                ];
			return $result;
		}
        $response = $this->doUpload($package);print_r($response);
        if ($response['status'] != 0) {
            preg_match('/<jobNo.*>(.*)<\/jobNo>/isU', $response['msg'], $shippingcode);
            preg_match('/<success.*>(.*)<\/success>/isU', $response['msg'], $restatus);
            if ($restatus[1] == 'true') {//成功获取追踪号
                $result = [
                    'code' => 'success',
                    'result' => $shippingcode[1]
                ];
            } else {//获取追踪号失败
                $result = [
                    'code' => 'error',
                    'result' => 'error description.'
                ];
            }
        }
        return $result;
    }

    public function doUpload($package)
    {

        $countryCode = $package->shipping_country;//发货国家简码
        $countryCode = $countryCode == "UK" ? "GB" : $countryCode;
        $buyer_name = $package->shipping_firstname . " " . $package->shipping_lastname;
        $totalCount = 0;//内件数量
        $totalWeight = 0;//货品重量，千克（三位小数）
        $totalValue = 0;//货品申报价值(两位小数)  暂时表中数据不确定准哪里
        $itemStr = "";
        $declareNameEn = "";
        foreach ($package->items as $key => $item) {
            $totalCount += $item->quantity;
            $totalWeight += $item->item->weight;
            $totalValue += $item->item->product->declared_value;
            $descrName = $item->item->product->declared_en;
            if ($key == 0) {
                $declareNameEn = $descrName;
            }
            $itemStr .= "<item>
                              <descrName><![CDATA[" . $descrName . "]]></descrName>
                              <pcs>" . $item->quantity . "</pcs>
                              <unitPrice>" . $item->orderItem->price . "</unitPrice>
                              <totalPrice>" . $item->orderItem->price * $item->quantity . "</totalPrice>
                              <cur>USD</cur>
            			  </item>";
        }
        $order = $package->order;
        $total_value = round($order->amount, 2);

        $content = "
        <logisticsEventsRequest>
        <logisticsEvent>
        <eventHeader>
            <eventType>LOGISTICS_PACKAGE_SEND</eventType>
            <eventMessageId><![CDATA[moonarstore" . $package->id . "-SLME-2016-COT-B76EFD991B19]]> </eventMessageId>
            <eventTime><![CDATA[" . date("Y-m-d H:i:s") . "]]></eventTime>
            <eventSource><![CDATA[SZE150401]]></eventSource>
            <eventTarget>COE</eventTarget>
        </eventHeader>
        <eventBody>
            <orders>
                <order>
                    <referenceID>moonarstore" . $package->id . "</referenceID>
                    <paymentType>PP</paymentType>

                    <pcs>" . $totalCount . "</pcs>

                    <destNo><![CDATA[" . $countryCode . "]]></destNo>
                    <date><![CDATA[" . date("Y-m-d H:i:s") . "]]></date>
                    <custNo><![CDATA[SZE150401]]></custNo>
                    <weight>" . $totalWeight . "</weight>
                    <declaredValue>" . $totalValue . "</declaredValue>
                    <declaredCurrency>USD</declaredCurrency>
                    <contents><![CDATA[" . $declareNameEn . "]]></contents>
                    <isReturnLabel>0</isReturnLabel>
                    <isInsure>0</isInsure>
                    <hub>" . $this->config['type'] . "</hub>0
                    <sendContact>
                        <companyName><![CDATA[" . $this->config['returnCompany'] . "]]></companyName>
                        <personName><![CDATA[" . $this->config['returnContact'] . "]]></personName>
                        <countryCode><![CDATA[" . $this->config['returnCountry'] . "]]></countryCode>
                        <phoneNumber><![CDATA[" . $this->config['returnPhone'] . "]]></phoneNumber>
                        <divisioinCode><![CDATA[" . $this->config['returnProvince'] . "]]></divisioinCode>
                        <city><![CDATA[" . $this->config['returnCity'] . "]]></city>
                        <address1><![CDATA[" . $this->config['returnAddress'] . "]]></address1>
                        <postalCode><![CDATA[" . $this->config['returnZipcode'] . "]]></postalCode>
                    </sendContact>
                    <receiverContact>
                        <companyName><![CDATA[" . substr($buyer_name,0,30) . "]]></companyName>
                        <personName><![CDATA[" . $buyer_name . "]]></personName>
                        <countryCode><![CDATA[" . $countryCode . "]]></countryCode>
                        <phoneNumber><![CDATA[" . $package->shipping_phone . "]]></phoneNumber>
                        <divisioinCode><![CDATA[" . $package->shipping_state . "]]></divisioinCode>
                        <city><![CDATA[" . $package->shipping_city . "]]></city>
                        <address1><![CDATA[" . $package->shipping_address . " ]]></address1>
                        <address2><![CDATA[" . $package->shipping_address1 . " ]]></address2>
                        <postalCode><![CDATA[" . $package->shipping_zipcode . "]]></postalCode>
                    </receiverContact>
                    <items>
                        " . $itemStr . "
                    </items>
                    </order>
                </orders>
            </eventBody>
        </logisticsEvent>
        </logisticsEventsRequest>";print_r($content);
        $accountDate = array(
            'UserId'=>'SZE150401',
            'UserPassword'=> 'SZE150401Mima20150902',
            'Key'=>'7891524B3896284F496775CCEA10F32C'
        );
        $content = urlencode($content);
        $headers = array("application/x-www-form-urlencoded; charset=gb2312");
        $postData = array();
        $url="http://112.74.141.18:9000/coeapi/coeSync/saveCoeOrder.do?Content=".$content."&UserId=".$accountDate['UserId']."&UserPassword=".$accountDate['UserPassword']."&Key=".$accountDate['Key'];
        $result = $this->curlPost($url, $postData, $headers);
        return $result;
    }
}