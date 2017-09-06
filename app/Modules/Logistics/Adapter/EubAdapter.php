<?php
/** 线上Eub
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-08-10
 * Time: 13:25
 */

namespace App\Modules\Logistics\Adapter;

 use App\Models\Channel\AccountModel;
class EubAdapter extends BasicAdapter
{


    public function __construct($config)
    {


        $str_path = 'https://api.apacshipping.ebay.com.hk/aspapi/v4/ApacShippingService?WSDL';
        $this->soapClient = new \SoapClient($str_path,array('stream_context' => stream_context_create(array('http'=>array('protocol_version'=>1.0)))));

        //var_dump($config);
        $this->callBase = array(
            'APIDevUserID'		 => '', //ebay开发者ID
            'AppID'   => '',
            'AppCert' => '',
            'Version' => '4.0.0',
            'Carrier' => 'CNPOST',
            'Service' => 'EPACK',
            'APISellerUserToken'=>'',
            'APISellerUserID'=>'',
        );

    }


    public function getTracking($package)
    {
        $response = $this->doUpload($package);
        if ($response['status'] != 0) {
            $result = [
                'code' => 'success',
                'result' =>$response['msg'] //跟踪号
            ];
        }else{
            $result = [
                'code' => 'error',
                'result' =>$response['msg'] //c错误信息
            ];
        }
        return $result;
    }



    public function doUpload($package){


        $account_info =  AccountModel::where('id',$package->channel_account_id)->first();
        $this->callBase['APIDevUserID'] = $account_info->ebay_developer_devid;//eBay token
        $this->callBase['AppID'] = $account_info->ebay_developer_appid;//eBay token
        $this->callBase['AppCert'] = $account_info->ebay_developer_certid;//eBay token
        $this->callBase['APISellerUserToken'] = $account_info->ebay_token;//eBay token
        $this->callBase['APISellerUserID'] = $account_info->ebay_eub_developer;//卖家eBay帐户

        $emailTemplateInfo = $package->logistics->emailTemplate;

        $this->eMSPickUpType = $emailTemplateInfo->eub_transport_type;


        $this->pickUpAddress = array( //揽收地址信息
            'Contact' => $emailTemplateInfo->eub_contact_name,
            'Company' => $emailTemplateInfo->eub_contact_company_name,
            'Street' => $emailTemplateInfo->eub_street,
            'District' => $emailTemplateInfo->eub_zone_code,
            'City' => $emailTemplateInfo->eub_city_code,
            'Province' => $emailTemplateInfo->eub_province_code,
            'Postcode' => $emailTemplateInfo->eub_zipcode,
            'CountryCode'=> 'CN',
            'Email' => $emailTemplateInfo->eub_email,
            'Mobile' => $emailTemplateInfo->eub_mobile_phone,
            'Phone' => $emailTemplateInfo->eub_phone
        );
        $this->shipFromAddress = array( //寄件人地址信息
            'Contact' => $emailTemplateInfo->eub_sender,
            'Company' => $emailTemplateInfo->eub_sender_company,
            'Street' => $emailTemplateInfo->eub_sender_street,
            'District' => $emailTemplateInfo->eub_sender_zone,
            'City' => $emailTemplateInfo->eub_sender_city,
            'Province' => $emailTemplateInfo->eub_sender_province,
            'Postcode' => $emailTemplateInfo->eub_sender_province_code,
            'CountryCode'=> 'CN',
            'Email' => $emailTemplateInfo->eub_sender_email,
            'Mobile' => $emailTemplateInfo->eub_sender_mobile_phone
        );
        $this->returnAddress = array( //退货地址信息
            'Contact' => $emailTemplateInfo->eub_return_contact,
            'Company' => $emailTemplateInfo->eub_return_company,
            'Street' => $emailTemplateInfo->eub_return_address,
            'District' => $emailTemplateInfo->eub_return_zone,
            'City' => $emailTemplateInfo->eub_return_city,
            'Province' => $emailTemplateInfo->eub_return_province,
            'Postcode' => $emailTemplateInfo->eub_return_zipcode,
            'CountryCode'=> 'CN'
        );



        $shipToAddress = array( //收件人地址信息
            'Contact' 	  => $package->shipping_firstname.' '.$package->shipping_lastname,
            'Street' 	  => $package->shipping_address . ' ' .$package->shipping_address1,
            'City' 		  => $package->shipping_city,
            'Province'    => $package->shipping_state,
            'CountryCode' => $package->shipping_country,
            'Postcode' 	  => $package->shipping_zipcode,
            'Phone' 	  => $package->shipping_phone,
            'Email' 	  => !empty($package->email)?$package->email:'report@moonarstore.com'
        );

        $tArray = array();
        foreach ($package->items as $key => $item) {
            $tArray[$item->orderItem->channel_sku][] = $item;
        }


        $new_data = array();
        $i = 0;
        foreach($tArray as $key => $value){

            $all_count = 0;
            $all_weight = 0.01;
            $all_value = 0.01;
            $all_sku ='';
            $title_en ='';
            $title_cn ='';

            foreach($value as $v){
                $all_count += $v->quantity;
                $all_sku[] =$v->orderItem->sku;
                $all_value += $v->item->product->declared_value*$v->quantity;
                $all_weight += $v->item->product->weight*$v->quantity;
                $title_en = $v->item->product->declared_en;
                $title_cn = $v->item->product->declared_cn;
            }

            $new_data[$i]['package_item'] = $value[0];
            $new_data[$i]['sku'] = implode('*',$all_sku);
            $new_data[$i]['value'] =$all_value>15?15:$all_value;
            $new_data[$i]['weight'] = $all_weight;
            $new_data[$i]['count'] = $all_count;
            $new_data[$i]['title_en'] = $title_en;
            $new_data[$i]['title_cn'] = $title_cn;
            $new_data[$i]['from'] = 'China';
            $new_data[$i]['fromCode'] = 'CN';
            $i++;

        }




        $ItemArray =[];

        foreach ($new_data as $ts) {
            $transactionID = empty($ts['package_item']->orderItem->transaction_id)?0: $ts['package_item']->orderItem->transaction_id;
            $ItemArray[] = array(
                'EBayItemID' 			=> $ts['package_item']->orderItem->orders_item_number,					//ebay物品号
                'EBayTransactionID' 	=> $transactionID,								//ebay交易号，拍卖的物品请输入0
                'EBayBuyerID' 			=> $package->order->aliexpress_loginId,								//ebay买家ID
                'EBayItemTitle' 		=> '',							//ebay商品标题
                'EBayEmail' 			=> $package->order->email,							//买家ebay邮箱
                'SoldQTY'			 	=> $ts['count'],							//卖出数量
                'PostedQTY' 			=> $ts['count'],							//寄货数量，不能为0
                'SalesRecordNumber' 	=> $package->order->channel_listnum, 						//用户从ebay上下载时ebay的销售编号
                'OrderSalesRecordNumber'=> '',						//订单销售编号，如果在ebay上合并订单，会产生一个新的SalesRecordNumber
                'OrderID' 				=> $package->order->channel_ordernum, 						//ebay合并订单时生成的一个新的Order ID
                'EBaySiteID' 			=> 0, 											//站点ID
                'ReceivedAmount' 		=> 15, 											//实际收到金额
                'PaymentDate' 			=> $package->order->payment_date ,  //买家付款日期
                'SoldPrice' 			=> 15, 											//卖出价格
                'SoldDate' 				=>$package->order->payment_date,  //卖出日期
                'CurrencyCode' 			=> $package->order->currency, 						//货币符号
                'EBayMessage' 			=> '', 											//买家 eBay 留言
                'PayPalEmail' 			=> '', 											//买家 PayPal 电邮地址
                'PayPalMessage' 		=> '', 											//买家 PayPal 留言
                'Note' 					=> '', 											//附注
                'SKU' 					=> array( 										//产品报关信息
                    'SKUID' 			=> $ts['sku'],
                    'DeclaredValue' 	=> $ts['value'],  //物品申报价值
                    'Weight' 			=> $ts['weight'], //物品重量
                    'CustomsTitle' 		=> $ts['title_cn'].'('.$ts['sku'].'*'.$ts['count'].')'.date('Y/m/d'), //中文报关名称+转接头接口
                    'CustomsTitleEN' 	=> $ts['title_en'],//英文报关名称
                    'OriginCountryCode' => $ts['fromCode'],//原产地国家代码
                    'OriginCountryName' => $ts['from'] //原产地
                )
            );
        }

        $xmlArray = array(
            'MessageID' => $package->id,
            'OrderDetail' => array(
                'EMSPickUpType' =>$this->eMSPickUpType,
                'PickUpAddress' => $this->pickUpAddress,
                'ShipFromAddress' => $this->shipFromAddress,
                'ShipToAddress' => $shipToAddress,
                'ReturnAddress' => $this->returnAddress,
                'ItemList' =>  array(
                    'Item' => $ItemArray
                )
            )
        );


        $xmlArray = array_merge($this->callBase, $xmlArray);

        $xml = array(
            "AddAPACShippingPackageRequest" => $xmlArray
        );

        $call = $this->soapClient->AddAPACShippingPackage($xml);

        $result = $call->AddAPACShippingPackageResult;
        if ('Success' == $result->Ack) {
            $return['status'] = 1;
            $return['msg'] = $result->TrackCode;
        }else{
            $return['status'] = 0;
            $error = '';
            if(isset($result->NotificationList->Notification)){
                foreach($result->NotificationList->Notification as $err){
                    $error .=' '.(string)$err->Message;
                }
            }
            $return['msg'] = $error;
        }
        return $return;
    }
}