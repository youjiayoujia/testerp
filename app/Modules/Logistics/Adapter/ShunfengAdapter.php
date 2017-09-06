<?php
/**  顺丰物流下单
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-06
 * Time: 18:08
 */

namespace App\Modules\Logistics\Adapter;
class ShunfengAdapter extends BasicAdapter
{


    public function __construct($config)
    {

        $this->_input_code = $config['userId'];
        $this->_check_code = $config['key'];
        $this->_sendstarttime = date('Y-m-d') . ' 22:00:00';
        $this->_url = $config['url'];
        $this->sendInfo['j_company'] = $config['returnCompany'];
        $this->sendInfo['j_contact'] = $config['returnContact'];
        $this->sendInfo['j_tel'] = $config['returnPhone'];
        $this->sendInfo['j_address'] = $config['returnAddress'];
        $this->sendInfo['j_province'] = $config['returnProvince'];
        $this->sendInfo['j_city'] = $config['returnCity'];
        $this->sendInfo['j_post_code'] = $config['returnZipcode'];
        $this->sendInfo['j_country'] = $config['returnCountry'];
        $this->sendInfo['custid'] = '';

        $this->_express_type = !empty($config['type']) ? $config['type'] : 9; // 9是平邮 10是挂号
        $this->_order_prefix = 'V3SLME';
        $this->SoapClient = new  \SoapClient($this->_url);

    }

    public function getTracking($package)
    {
        $response = $this->doUpload($package);
        if ($response['status'] != 0) {
            $shipping_code = trim($response['msg']->Body->OrderResponse['mailno']);        //运单号
            $filter_result = trim($response['msg']->Body->OrderResponse['filter_result']); //筛单结果：1-人工确认，2-可收派 3-不可以收派
            $remark = trim($response['msg']->Body->OrderResponse['remark']);               //1-收方超范围，2-派方超范围，3-其他原因
            //如果是挂号的话，一定要要把这个字段保存下来哈
            $agent_mailno = trim($response['msg']->Body->OrderResponse['agent_mailno']);   //代理运单号 --挂号的可能会产生

            if ($filter_result == 2) {//在可收派范围内
                $result = [
                    'code' => 'success',
                    'result' =>$shipping_code //跟踪号
                ];
                if(!empty($agent_mailno)){
                    $result['result_other'] = $agent_mailno;
                }

                if( $this->_express_type == 10){ //挂号 传到平台上的值放在result
                    $result = [
                        'code' => 'success',
                        'result' =>$agent_mailno,
                        'result_other' => $shipping_code
                    ];

                }


            }else{
                $msg = '';
                switch ($remark){
                    case 1:
                        $msg = '收方超范围';
                        break;
                    case 2:
                        $msg =  '派方超范围';
                        break;
                    case 3:
                        $msg = '其他原因';
                        break;
                    default:
                        $msg = '';
                }
                if ($filter_result == 1) {
                    $msg = '等待人工确认,'.$msg;
                }elseif ($filter_result == 3){
                    $msg = '不可以收派,'.$msg;
                }

                $result =[
                    'code' => 'error',
                    'result' => $msg
                ];
            }
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

        $return = [];
        $country_codeArr = array(//顺丰俄罗斯能到达的国家，国家简码
            'RU', 'LT', 'LV', 'EE', 'SE', 'NO', 'FI', 'BY', 'UA', 'PL'
        );

        if (!in_array($package->shipping_country, $country_codeArr)) {
            $return['status'] = 0;
            $return['msg'] = '不在运输国家范围，请确认';
            return $return;

        }

        $cityCodeArr = array(//对应国家简码的城市代码
            'RU' => 'MOW', 'LT' => 'VNO', 'LV' => 'RIX',
            'EE' => 'TLL', 'SE' => 'ARN', 'NO' => 'OSL',
            'FI' => 'HEL', 'BY' => 'MSQ', 'UA' => 'KBP',
            'PL' => 'WAW'
        );


        $deliverycode = $cityCodeArr[$package->shipping_country];
        $product_str = '';
        $count = 0;
        $amount = 0;
        foreach ($package->items as $key => $item) {
            $count = $count + $item->quantity;
            $amount = $amount + $item->item->product->declared_value;
            $products_declared_en = $item->item->product->declared_en;
        }
        $amount = $amount > 20 ? 20 : $amount;
        $amount = $amount=0?0.02:$amount;
        $product_str .= '<Cargo name="' . htmlspecialchars($products_declared_en) . '" count="1" unit="u" weight="' . $package->weight . '" amount="' . $amount . '" currency="USD" source_area="CN"></Cargo>';

        $order_str = '<?xml version="1.0" encoding="UTF-8"?>
                        <Request service="OrderService" lang="zh-CN">
	    <Head>' . $this->_input_code . ',' . $this->_check_code . '</Head>
	    <Body>
    	<Order orderid="' . $this->_order_prefix . $package->id . '" express_type="' . $this->_express_type . '" d_company="' . htmlspecialchars($package->shipping_firstname . ' ' . $package->shipping_lastname) . '" d_contact="' . htmlspecialchars($package->shipping_firstname . ' ' . $package->shipping_lastname) . '"
    	d_tel="' . $package->shipping_phone . '" d_address="' . htmlspecialchars($package->shipping_address) . ($package->shipping_address1 ? ' ' . htmlspecialchars($package->shipping_address1) : '') . '"
    	d_province="' . htmlspecialchars($package->shipping_state) . '" d_city="' . htmlspecialchars($package->shipping_city) . '" d_country="' . htmlspecialchars($package->shipping_country) . '" d_deliverycode="' . $deliverycode . '"
    	d_post_code="' . $package->shipping_zipcode . '" sendstarttime="' . $this->_sendstarttime . '" cargo_total_weight="' . $package->weight . '"
    	parcel_quantity="1" declared_value="' . $amount . '" pay_method="1" declared_value_currency="USD"
   		j_company="' . $this->sendInfo['j_company'] . '" j_contact="' . $this->sendInfo['j_contact'] . '" j_tel="' . $this->sendInfo['j_tel'] . '"
   		j_address="' . $this->sendInfo['j_address'] . '" j_province="' . $this->sendInfo['j_province'] . '" j_city="' . $this->sendInfo['j_city'] . '"
   		j_post_code="' . $this->sendInfo['j_post_code'] . '" j_country="' . $this->sendInfo['j_country'] . '" custid="' . $this->_input_code . '">';

        $order_str .= $product_str;
        $order_str .= '
   		</Order>
   	    </Body>
        </Request>';

        $call = $this->SoapClient->sfexpressService(array('arg0' => $order_str));
        $responseXml = $call->return;//返回的xml信息，数据很小
        $result = simplexml_load_string($responseXml);
        if ($result->Head == 'OK') {
            //获取追踪号成功，直接确认订单信息
            $return['status'] = 1;
            $return['msg'] = $result;

        } elseif ($result->Head == 'ERR') {
            $return['status'] = 0;
            $return['msg'] = (string)$result->ERROR;
        }

        return $return;


    }

}