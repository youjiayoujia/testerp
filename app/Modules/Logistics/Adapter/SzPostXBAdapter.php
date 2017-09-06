<?php
namespace App\Modules\Logistics\Adapter;

class SzPostXBAdapter extends BasicAdapter
{
    public function __construct($config){
         $this->ShipServerUrl = $config['url'];
         $this->ecCompanyId =  $config['userId'];
         $this->scret = $config['userPassword'];
         $this->mailType = 'SALAMOER';
         $this->ServerUrl = 'http://shipping.11185.cn:8000/mqrysrv/OrderImportMultiServlet';
//         $this->ServerUrl = 'http://219.134.187.38:8089/mqrysrv/OrderImportMultiServlet';
//         $this->ShipServerUrl = 'http://219.134.187.38:8089/produceWeb/barCodesAssgineServlet';
//         $this->ecCompanyId='44030324695000|5180120245';
//         $this->scret = '8U3Y0jt93C98u7036190';
//         $this->mailType = 'SALAMOER';
        $this->sendInfo = array(
            'j_company'  => $config['returnCompany'],                        //寄件人公司
            'j_contact'  => $config['returnContact'],                        //寄件人
            'j_tel'      => $config['returnPhone'],                          //电话
            'j_address1' => $config['returnAddress'],                        //地址
            'j_province' => $config['returnProvince'],                       //省
            'j_city'     => $config['returnCity'],                           //市
            'j_post_code'=> $config['returnZipcode'],                        //邮编
            'j_country'  => $config['returnCountry'],                        //国家
            //'custid' => '7555769565'
        );
     }
     
    
    public function getTracking($package){
        $orderStr = '';
        $dateTime = date('Y-m-d H:i:s');
        list($name, $channel) = explode(',',$package->logistics->type);
        $orderStr .= '{"ecCompanyId":"'.$this->ecCompanyId.'","eventTime":"'.$dateTime.'","logisticsOrderId":"'.$package->id.'","LogisticsCompany":"POST","LogisticsBiz":"'.$channel.'","mailType":"'.$this->mailType.'","faceType":"1"},';
        
        $orderStr = trim($orderStr,',');
        $orderStr = '{"order": ['.$orderStr.']}';
        $orderStr = json_decode($orderStr);
        $orderStr = json_encode($orderStr);
        $newdata =  base64_encode(pack('H*', md5($orderStr.$this->scret)));
        $url = $this->ShipServerUrl;
        $postD = array();
        $postD['logisticsOrder'] =$orderStr;
        $postD['data_digest'] =$newdata;
        $postD['msg_type'] ='B2C_TRADE';
        $postD['ecCompanyId'] =$this->ecCompanyId;
        $postD['version'] ='1.0';
        
        $url1 = '';
        foreach($postD as $key=>$v){
            $url1.=$key.'='.$v.'&';
        }
        $url1 = trim($url1,'&');
        $postD = http_build_query($postD);
        $result = $this->postCurlHttpsData($url,$url1);
        $result = json_decode($result,true);  
        echo "<pre>";
        print_r($result);
        if($result['return_success'] == 'true'){
            $barCodeList = $result['barCodeList'];
            foreach($barCodeList as $v){
                $shipcode = $v['bar_code'];
                $orderId = $v['logisticsOrderId'];
                $res = $this->sendOrder($package, $shipcode);
                if($res === true){
                    return array('code' => 'success', 'result' => $shipcode);
                }else{
                    return array('code' => 'error','result' => 'error description.');
                }               
            }
        }else{
            return array('code' => 'error','result' => 'error description.');
        }
            
        
    }

    public function sendOrder($package,$shipcode){

        $proStr = '';
        $productNum = 0;
        
        /*$products_declared_cn = $package->items ? $package->items->first()->item->product->declared_cn : '裙子';
        $products_declared_en = $package->items ? $package->items->first()->item->product->declared_en : 'skirt';
        $totalWeight          = $package->total_weight * 1000;
        $totalValue           = $package->total_price * 1000;
        $category_name        = $package->items ? ($package->items->first()->item->catalog ? $package->items->first()->item->catalog->name : '裙子') : '裙子';      //获取分类信息
        $category_name_en     = $package->items ? ($package->items->first()->item->catalog ? $package->items->first()->item->catalog->c_name : 'skirt') : 'skirt';
        */
        $productId            = $package->items ? $package->items->first()->item->product_id : '';
        $totalWeight          = $package->total_weight * 1000;
        $totalValue           = $package->total_price * 1000;
        foreach ($package->items as $packageItem) {
            $productNum = $packageItem->quantity;
            $products_declared_cn = $packageItem->item->product->declared_cn;
            $products_declared_en = $packageItem->item->product->declared_en;
            $category_name        = $packageItem->item->catalog ? $packageItem->item->catalog->c_name : '裙子';      //获取分类信息
            $category_name_en     = $packageItem->item->catalog ? $packageItem->item->catalog->name : 'skirt';
            $single_weight        = $packageItem->quantity * ($packageItem->item ? $packageItem->item->weight : 0);
            $single_value         = $packageItem->quantity * ($packageItem->orderItem ? $packageItem->orderItem->price : 0);
            /*if(mb_strlen($category_name) > 60){
                $category_name = mb_substr($category_name,4,mb_strlen($category_name),'utf-8');     //组装申报中文名时，有些ERP品类的长度大于API的限定长度（60,中文字符占3个字符），截取处理。
            }*/
            $proStr .='<product>';
            $proStr .='<productNameCN>'.$products_declared_cn.'</productNameCN>';
            $proStr .='<productNameEN>'.$products_declared_en.'</productNameEN>';
            $proStr .='<productQantity>'.$productNum.'</productQantity>';
            $proStr .='<productCateCN>'.$category_name.'</productCateCN>';
            $proStr .='<productCateEN>'.$category_name_en.'</productCateEN>';
            $proStr .='<productId>'.$productId.'</productId>';
            $proStr .='<producingArea>CN</producingArea>';
            $proStr .='<productWeight>'.($single_weight * 1000).'</productWeight>';
            $proStr .='<productPrice>'.($single_value * 1000).'</productPrice>';
            $proStr .='</product>';
        }
        
        /*$proStr .='<product>';
        $proStr .='<productNameCN>'.$products_declared_cn.'</productNameCN>';
        $proStr .='<productNameEN>'.$products_declared_en.'</productNameEN>';
        $proStr .='<productQantity>'.$productNum.'</productQantity>';
        $proStr .='<productCateCN>'.$category_name.'</productCateCN>';
        $proStr .='<productCateEN>'.$category_name_en.'</productCateEN>';
        $proStr .='<productId>'.$productId.'</productId>';
        $proStr .='<producingArea>CN</producingArea>';
        $proStr .='<productWeight>'.$totalWeight.'</productWeight>';
        $proStr .='<productPrice>'.$totalValue.'</productPrice>';
        $proStr .='</product>';
               
        if($package->warehouse_id == 3){
            //深圳仓
            $this->sendInfo = array(
                'j_company' => 'SALAMOER',                          //寄件人公司
                'j_contact' => 'huangchaoyun',                      //寄件人
                'j_tel' => '18038094536',                           //电话
                'j_address1' => '2nd Floor,Buliding 6,No. 146 Pine Road,Mengli Garden Industrial, Longhua District',//地址
                'j_address2' => 'No.41',
                'j_address3' =>' Wuhe Road South LONGGANG',
                'j_province' => 'GUANGDONG',                        //省
                'j_city' => 'SHENZHEN',                             //市
                'j_post_code' => '518129',                          //邮编
                'j_country' => 'CN',                                //国家
                'custid' => '7555769565'
            );
        }elseif($package->warehouse_id == 4){
            //义乌仓
            $this->sendInfo=array(
                'j_company' => 'JINHUA MOONAR',                     //寄件人公司
                'j_contact' => 'xiehongjun',                        //寄件人
                'j_tel' => '15024520515',                           //电话
                'j_address1' => 'Buliding 1-4, Jinyi Postal Park, No.2011',//地址
                'j_address2' => 'No.2011',//地址
                'j_address3' =>'JinGangDaDao West, JINDONG',
                'j_province' => 'ZHEJIANG',                         //省
                'j_city' => 'JINHUA',                               //市
                'j_post_code' => '321000',                          //邮编
                'j_country' => 'CN',                                //国家
                'custid' => '5796625949'
            );
        }*/
        $package->shipping_phone = str_replace("+","",$package->shipping_phone);
        $dateTime = date('Y-m-d H:i:s');
        $batchNo = date('Ymd');
        $orderId = $package->id;       //内单号
        list($name, $channel) = explode(',',$package->logistics->type);
        $str = '<logisticsEventsRequest>';
        $str .='<logisticsEvent>';
        $str .='<eventHeader>';
        $str .='<eventType>LOGISTICS_BATCH_SEND</eventType>';
        $str .='<eventTime>'.$dateTime.'</eventTime>';
        $str .='<eventSource>taobao</eventSource>';
        $str .='<eventTarget>NPP</eventTarget>';
        $str .='</eventHeader>';
        $str .='<eventBody>';
        $str .='<order>';
        $str .='<orderInfos>'.$proStr.'</orderInfos>';
        $str .='<ecCompanyId>'.$this->ecCompanyId.'</ecCompanyId>';
        $str .='<logisticsOrderId>'.$orderId.'</logisticsOrderId>';
        $str .='<isItemDiscard>true</isItemDiscard>';
        $str .='<mailNo>'.$shipcode.'</mailNo>';
        $str .='<LogisticsCompany>POST</LogisticsCompany>';
        $str .='<LogisticsBiz>'.$channel.'</LogisticsBiz>';
        $str .='<ReceiveAgentCode>POST</ReceiveAgentCode>';
        $str .='<Rcountry>'.$package->shipping_country.'</Rcountry>';
        $str .='<Rcity>'.$package->shipping_city.'</Rcity>';
        $str .="<Raddress>".$package->shipping_address.' '.$package->shipping_address1."</Raddress>";
        $str .='<Rpostcode>'.$package->shipping_zipcode.'</Rpostcode>';
        $str .="<Rname>".$package->shipping_firstname . ' ' . $package->shipping_lastname."</Rname>";
        $str .='<Rphone>'.$package->shipping_phone.'</Rphone>';
        $str .='<Sname>'.$this->sendInfo['j_contact'].'</Sname>';
        $str .='<Sprovince>'.$this->sendInfo['j_province'].'</Sprovince>';
        $str .='<Scity>'.$this->sendInfo['j_city'].'</Scity>';
        $str .='<Saddress>'.$this->sendInfo['j_address1'].'</Saddress>';
        $str .='<Sphone>'.$this->sendInfo['j_tel'].'</Sphone>';
        $str .='<Spostcode>'.$this->sendInfo['j_post_code'].'</Spostcode>';
        $str .='<Itotleweight>'.$totalWeight.'</Itotleweight>';
        $str .='<Itotlevalue>'.$totalValue.'</Itotlevalue>';
        $str .='<totleweight>'.$totalWeight.'</totleweight>';
        $str .='<hasBattery>false</hasBattery>';
        $str .='<country>CN</country>';
        $str .='<mailKind>3</mailKind>';
        $str .='<mailClass>l</mailClass>';
        $str .='<batchNo>'.$batchNo.'</batchNo>';
        $str .='<mailType>'.$this->mailType.'</mailType>';
        $str .='<faceType>2</faceType>';
        $str .='<undeliveryOption>2</undeliveryOption>';
        $str .='</order>';
        $str .='</eventBody>';
        $str .='</logisticsEvent>';
        $str .='</logisticsEventsRequest>';
                
       /*$str .="<logisticsEventsRequest><logisticsEvent>
<eventHeader>
<eventType>LOGISTICS_BATCH_SEND</eventType>
<eventTime>".$dateTime."</eventTime>
<eventSource>taobao</eventSource>
<eventTarget>NPP</eventTarget>
</eventHeader>
<eventBody>
<order>
<orderInfos>
".$proStr."
</orderInfos>
<ecCompanyId>".$this->ecCompanyId."</ecCompanyId>
<logisticsOrderId>".$orderId."</logisticsOrderId>
<isItemDiscard>true</isItemDiscard>
<mailNo>".$shipcode."</mailNo>
<LogisticsCompany>POST</LogisticsCompany>
<LogisticsBiz>".$channel."</LogisticsBiz>
<ReceiveAgentCode>POST</ReceiveAgentCode>
<Rcountry>".$package->shipping_country."</Rcountry>
<Rcity>".$package->shipping_city."</Rcity>
<Raddress>".$package->shipping_address.' '.$package->shipping_address1."</Raddress>
<Rpostcode>".$package->shipping_zipcode."</Rpostcode>
<Rname>".$package->shipping_firstname . ' ' . $package->shipping_lastname."</Rname>
<Rphone>".$package->shipping_phone."</Rphone>
<Sname>".$this->sendInfo['j_contact']."</Sname>
<Sprovince>".$this->sendInfo['j_province']."</Sprovince>
<Scity>".$this->sendInfo['j_city']."</Scity>
<Saddress>".$this->sendInfo['j_address1']."</Saddress>
<Sphone>".$this->sendInfo['j_tel']."</Sphone>
<Spostcode>".$this->sendInfo['j_post_code']."</Spostcode>
<Itotleweight>". $totalWeight."</Itotleweight>
<Itotlevalue>". $totalValue."</Itotlevalue>
<totleweight>". $totalWeight."</totleweight>
<hasBattery>false</hasBattery>
<country>CN</country>
<mailKind>3</mailKind>
<mailClass>L</mailClass>
<batchNo>".$batchNo."</batchNo>
<mailType>".$this->mailType."</mailType>
<faceType>2</faceType>
<undeliveryOption>2</undeliveryOption>
</order>
</eventBody>
</logisticsEvent>
</logisticsEventsRequest>";*/ 
//         $obj = simplexml_load_string($str);
//         print_r($obj);
        $str=preg_replace("/&|’|'|,/",' ',$str);     
        $newdata =  base64_encode(pack('H*', md5($str.$this->scret)));
        $url = $this->ServerUrl;
        $postD = 'logistics_interface='.$str.'&data_digest='.$newdata.'&msg_type=B2C_TRADE&ecCompanyId='.$this->ecCompanyId.'&version=2.0';     
        /*$postD = array();
        $postD['logistics_interface'] = $str;
        $postD['data_digest']         = $newdata;
        $postD['msg_type']            = 'B2C_TRADE';
        $postD['ecCompanyId']         = $this->ecCompanyId;
        $postD['version']             = '2.0';*/
        echo $postD;
        $result = $this->postCurlHttpsData($url,$postD);
        $result = $this->XmlToArray($result);
        print_r($result);
        if($result['responseItems']['response']['success'] == 'true'){
            return true;        
        }else{
           return false;
        }
    }
    
    public function postCurlHttpsData($url, $data) { // 模拟提交数据函数
        $headers = array(     
            'application/x-www-form-urlencoded; charset=UTF-8'
        );
    
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
        //curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
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

?>