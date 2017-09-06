<?php
namespace App\Modules\Logistics\Adapter;

/**
 * 燕文物流下单
 * @author guoou
 * @abstract 2016/8/11
 */

class YwAdapter extends BasicAdapter
{
    public function __construct($config){
        $this->serverUrl = $config['url'];
        $this->userId = $config['userId'];
        $this->token = $config['key'];
    }
    
    public function getTracking($package){
        $response = $this->doUpload($package);
        if ($response['status'] != 0) {
            $result = [
                'code'         => 'success',
                'result'       => $response['tracking_no'],                    //跟踪号
                'result_other' => $response['logistics_order_number']   //燕文自编号
            ];            
        }else{
            $result =[
                'code'   => 'error',
                'result' => $response['msg']
            ];
        }        
        return $result;
    }
    
    public function doUpload($package){
        $epcode = '';
        $quantity = 0;
        $requestXmlBody  = '';       
     
        foreach ($package->items as $packageItem) {
            $quantity += $packageItem->quantity;
            $warehouse_position_id = $packageItem->warehousePosition ? $packageItem->warehousePosition->name : '-';
            $products_sku = $packageItem->item ? $packageItem->item->sku : '';
        }
        $products_declared_cn = $package->items ? $package->items->first()->item->product->declared_cn : '';
        $products_declared_en = $package->items ? $package->items->first()->item->product->declared_en : '';
        $products_declared_value = $package->items->first()->item->declared_value;
        $memo_str = $products_sku . ' * ' . $quantity . " (". $warehouse_position_id . ")\r\n";      
        $receiver_name = $package->shipping_firstname.' '.$package->shipping_lastname;
        $address = $package->shipping_address.' '.$package->shipping_address1;
        list($name,$channel) =  explode(',',$package->logistics->type);
        $userID = $this->userId;
        $order_number = $package->id . $this->rand_string(5);
        $weight = $package->total_weight * 1000;
        $requestXmlBody =   "<ExpressType>
                                <Epcode>$epcode</Epcode>
                                <Userid>$userID</Userid>
                                <Channel>$channel</Channel>                
                                <UserOrderNumber>$package->order_id</UserOrderNumber>
                                <SendDate>$package->shipped_at</SendDate>
                                <Receiver>
                                    <Userid>$userID</Userid>
                                    <Name>$receiver_name</Name>
                                    <Phone>$package->shipping_phone</Phone>
                                    <Mobile>NULL</Mobile>
                                    <Email>". $package->order->email ."</Email>
                                    <Company>NULL</Company>
                                    <Country>$package->shipping_country</Country>
                                    <Postcode>$package->shipping_zipcode</Postcode>
                                    <State>$package->shipping_state</State>
                                    <City>$package->shipping_city</City>
                                    <Address1>$address</Address1>
                                    <Address2>NULL</Address2>
                                </Receiver>
                                <Memo>$memo_str</Memo>
                                <Quantity>$quantity</Quantity>
                                <GoodsName>  
                                    <Id>$order_number</Id>
                                    <Userid>$userID</Userid>
                                    <NameCh>". $products_declared_cn ."</NameCh>
                                    <NameEn>" . substr($products_declared_en, 0, 190) . "</NameEn>   
                                    <MoreGoodsName>" . $products_declared_cn . "</MoreGoodsName>
                                    <Weight> $weight </Weight>
                                    <DeclaredValue>$products_declared_value</DeclaredValue>
                                    <DeclaredCurrency>". $package->order->currency ."</DeclaredCurrency>                                  
                                </GoodsName>
                            </ExpressType>"; 
        $url = $this->serverUrl . 'Users/'.$this->userId.'/Expresses';
        $result = $this->sendHttpRequest($url, 1, $requestXmlBody);     
        $result_xml = simplexml_load_string($result);
        if ( $result_xml->Response->Success == 'true' ) {      
            $epcodeNode = $result_xml->CreatedExpress->Epcode;
            $YWcode = $result_xml->CreatedExpress->YanwenNumber;
            return array('status' => 1,'tracking_no' => $epcodeNode,'logistics_order_number' => $YWcode);                             
        }else{
            $errorMsg = $result_xml->Response->ReasonMessage;            
            return array('status' => 0,'msg' => $errorMsg);
        }
    }
    
    public function sendHttpRequest($url, $post, $requestBody)
    {
        $headers = array(
            'Authorization: basic '. $this->token ,
            'Content-Type: text/xml; charset=utf-8',
        );
        $connection = curl_init();   
        
        curl_setopt($connection, CURLOPT_VERBOSE, 1);  
        curl_setopt($connection, CURLOPT_URL, $url);     
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
        if ($post) {
            curl_setopt($connection, CURLOPT_POST, 1);         
            curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
        }
    
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($connection, CURLOPT_TIMEOUT, 30);   
        $response = curl_exec($connection);    
        $curl_errno = curl_errno($connection);    
        $curl_error = curl_error($connection);         
        curl_close($connection);    
        if( $curl_errno > 0 ){
            return array('msg' => "请求错误: ($curl_errno): $curl_error\n", 'result' => false);
        }
    
        return $response;
    }
    
    public function rand_string($len, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $string = '';
        for ($i = 0; $i < $len; $i++)
        {
            $pos = rand(0, strlen($chars)-1);
            $string .= $chars{$pos};
        }
        return $string;
    }
    
    
}

?>