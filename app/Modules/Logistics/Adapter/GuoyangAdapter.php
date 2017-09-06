<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2017-02-09
 * Time: 09:42
 */
namespace App\Modules\Logistics\Adapter;
class GuoYangAdapter extends BasicAdapter
{
    public function __construct($config)
    {
        $this->authtoken =  $config["userId"];
        $this->clientcode =  $config["key"];
        $this->typecode =  $config["key"];
        $this->channelcode = $config['type'];
        $this->apiurl = $config['url'];

    }

    public function getTracking($package)
    {
        $response = $this->doUpload($package);
        if (isset($response['orderlist']['0']['status']) && $response['orderlist']['0']['status'] == 1 && isset($response['orderlist']['0']['billid'])) {
            $result = [
                'code' => 'success',
                'result' => $response['orderlist']['0']['billid']//跟踪号
            ];
        } else {
            $error = '';
            if (isset($response['errormsg']) && !empty($response['errormsg'])) {
                $error =  $response['errormsg'];
            }

            if (isset($response['orderlist']['0']['errormsg']) && !empty($response['orderlist']['0']['errormsg'])) {
                $error = $response['orderlist']['0']['errormsg'];
            }

            $result =[
                'code' => 'error',
                'result' => empty($error)?$error:'未知错误'
            ];
        }
        return $result;
    }

    public function doUpload($package)
    {
        $request_json = '';
        $recweight = 0;
        $recvalue = 0;
        $request_item = '';     //sku_request_info
        foreach ($package->items as $key => $item) {
            $recweight = $item->item->weight * $item->quantity;
            $recvalue = $recvalue + $item->item->product * $item->quantity;
            $request_item .= '{
									"itemname":"' . trim($item->item->name) . '",
									"itemcustoms":"' . trim($item->item->product->declared_en) . '",
									"itemnum":"' . trim($item->quantity) . '",
									"itemvalue":"' . trim($item->item->product->declared_value) . '",
									"itemprodno":".",
									"itemweight":"' . trim($item->item->weight) . '",
									"currency":"USD"
								}';
        }
        if ($recvalue > 22) {
            $recvalue = 22;
        }
        $address = $package->shipping_address . $package->shipping_address1;
        $buyer_state = !empty($package->shipping_state) ? $package->shipping_state : $package->shipping_city;
        $request_json .= '{
							      "authtoken": "' . $this->authtoken . '",,
								  "clientcode": "' . $this->clientcode . '",
								  "typecode": "' . $this->typecode . '",
								   "orderlist": [
								      {
								        "refernumb":"V3SLME' . $package->id . '",
								        "billid":"",
								        "channelcode":"' . $this->channelcode . '",
								        "reccorp":".",
								        "recname":"' . trim($package->shipping_firstname . $package->shipping_lastname) . '",
								        "countrycode":"' . trim($package->shipping_country) . '",
								        "recprovince":"' . trim($buyer_state) . '",
								        "reccity":"' . trim($package->shipping_city) . '",
								        "recadd":"' . trim($address) . '",
								        "recpost":"' . trim($package->shipping_zipcode) . '",
								        "recphone":"' . trim($package->shipping_phone) . '",
								        "recemail":"' . trim($package->email) . '",
								        "weight":"' . $recweight . '",
								        "ordervalue":"' . $recvalue . '",
								        "item":
								        [
								        ' . $request_item . '
								        ]
								  }
								  ]
								  }';

        $apiResult = $this->getCurlData($this->apiurl, $request_json);
        return json_decode($apiResult, true);
    }

    public function getCurlData($url, $data = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $content = curl_exec($curl);
        curl_close($curl);
        return $content;
    }
}