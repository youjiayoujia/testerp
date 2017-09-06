<?php

namespace App\Http\Controllers\Publish\Smt;


use App\Models\ErpSystem;
use App\Models\ProductModel;
use App\Models\ErpSalesPlatform;
use App\Models\CurrencyModel;
use App\Models\Logistics\ZoneModel;
use App\Modules\Channel\Adapter\AliexpressAdapter;
use App\Models\Publish\Smt\smtProductSku;

class SmtProductsController 
{
    private $_product_statues_type = array("onSelling", "offline", "auditing", "editingRequired"); // 商品业务状态
    private $_CNYExchangeRate; // 人民币汇率
    private $_lowerProfitRate = 10; //最低利润率 sw20150427 用来计算最低售价
    
    public function __construct(){
        $this->_CNYExchangeRate = $this->getExchangeRateByType('RMB');
    }
    
    /**
     * 从速卖通SKU中提取不带前后缀的SKU
     * @param $skuCode
     * @return mixed
     */
    public function getSkuCode($skuCode){
        $skuTemp  = $skuCode;
        $skuTempA = (strpos($skuTemp,"*") !== false) ? strpos($skuTemp,"*") : -1;
        $skuTempB = (strpos($skuTemp,"#") !== false) ? strpos($skuTemp,"#") : strlen($skuTemp);
        $skuTemp  = substr($skuTemp,$skuTempA+1,$skuTempB-$skuTempA-1);
        return $skuTemp;
    }
    
    /**
     * 获取速卖通销售前缀
     * @param $sku
     * @return string
     */
    public function get_skucode_prefix($sku)
    {
        $len = 0;
        $prefix = '';
        if (($len = stripos($sku, '*')) > 0) {
            $prefix = substr($sku, 0, $len);
        }
        return strtoupper(trim($prefix));
    }
    
    public function parseDateString($str){
        return date ( 'Y-m-d H:i:s', strtotime ( mb_substr ( $str, 0, 14 ) ) );
    }
    
    /**
     * 获取SKU属性中的海外仓发货属性
     * @param $aeopSKUProperty
     * @return int
     */
    public function checkProductSkuAttrIsOverSea($aeopSKUProperty){
        $valId = 0;
        if (!empty($aeopSKUProperty)){
            foreach ($aeopSKUProperty as $property){
                if ($property['skuPropertyId'] == 200007763){ //发货地的属性ID
                    $valId = $property['propertyValueId'];
                    break;
                }
            }
        }
        return $valId;
    }
    
    /**
     * 获取定义的专为SMT打折定义的物流
     * @return array
     */
    public function _getSmtDefinedShipForDiscount(){
        $data = array();
        $sysInfo = ErpSystem::where('system_value_id',97)->first();
        if (empty($sysInfo)){ //没有数据，直接返回了
            return $data;
        }
    
        //开始解析下
        $shipList = explode(chr(13), $sysInfo->system_value);
        if (!empty($shipList)){
            foreach ($shipList as $row){
                list($pre, $val) = explode('|', $row);
    
                if (trim($pre) == 'gt15'){
                    $temp = explode(';', $val);
                    foreach($temp as $v){
                        list($k, $v) = explode(':', $v);
                        $data['gt15'][trim($k)] = trim($v);
                    }
                }else if (trim($pre) == 'le15'){
                    $temp = explode(';', $val);
                    foreach($temp as $v){
                        list($k, $v) = explode(':', $v);
                        $data['le15'][trim($k)] = trim($v);
                    }
                }
            }
        }
        return $data;
    }
    
    public function _buildSysSku($skuCode){
        $skus = $this->getSkuCode($skuCode) ;
    
        $sku_list = explode('+', $skus); // 处理组合的SKU：DA0090+DA0170+DA0137
        $sku_arr  = array();
        foreach ($sku_list as $value) {
            $len = strpos($value, '('); // 处理有捆绑的SKU：MHM330(12)
            $sku_new = $len ? substr($value, 0, $len) : $value;
            $sku_arr[] = $sku_new;
        }
        return !empty($sku_arr) ? $sku_arr : array('');
    }
    
    public function _countProductLowerPrice($sku, $price, $shipArray){
        if (empty($shipArray)){
            return array('status' => false, 'lowerPrice' => 0, 'info' => '没有定义相应物流');
        }
    
        //先判断SKU是否存在，不存在不计算
        $sku = str_replace('{YY}', '', $sku);
        $sku = trim($sku);
        $product = ProductModel::where('model',$sku)->first();
        if (empty($product)){
            return array('status' => false, 'lowerPrice' => 0, 'info' => 'SKU不存在');
        }
        //根据售价及产品信息来确定物流
        $shipmentId = $this->_chooseProductShip($product, $price, $shipArray); //获取物流
        if (empty($shipmentId)){
            return array('status' => false, 'lowerPrice' => 0, 'info' => '物流不存在');
        }
    
        //计算成本: 运费+采购成本+平台费
    
        //平台费率
        $salePlat    = ErpSalesPlatform::where('platID',5)->first()->toArray();// 获取速卖通平台费率等信息
        $platFee     = $salePlat['platOperateFee']; //平台操作费
        $platFeeRate = $salePlat['platFeeRate']; //平台费率
    
        //运费
        $shipFee = $this->getShipFee($shipmentId, $product->products_weight);
    
        //成本价
        $cost = $product->purchase_price;
        if ($shipFee <= 0 || $cost <= 0){
            return array('status' => false, 'lowerPrice' => 0, 'info' => '成本价或运费不存在');
        }
        $this->_CNYExchangeRate = CurrencyModel::where('code','RMB')->first()->rate;
        //售价 = ((成本价+运费+物流操作费)/美元汇率+ 平台固定操作费)/(1-利润率-成交费率)
        $lowerPrice = (($cost + $shipFee)/$this->_CNYExchangeRate + $platFee)/(1 - $this->_lowerProfitRate/100 - $platFeeRate/100);
        return array('status' => true, 'lowerPrice' => $lowerPrice, 'info' => '');
    }
    
    /**
     * 根据产品信息和售价来确定物流
     * @param $productInfo
     * @param $price
     * @param $shipArray
     * @return int
     */
    private function _chooseProductShip($productInfo, $price, $shipArray){
        $shipmentId = 0;
        if(count($productInfo->logisticsLimit)){
            foreach($productInfo->logisticsLimit as $logisticsLimit)
            $product_limit_type = $logisticsLimit->name;
        }else{
            return $shipmentId;
        }  
    
        if ($price > 10){
            if ($product_limit_type == '含电池')  {//带电
                $shipmentId = isset($shipArray['gt15']['battery']) ? $shipArray['gt15']['battery'] : 0;//151;
            }elseif ($product_limit_type == '含液体' || $product_limit_type == '含粉尘'){
                $shipmentId = isset($shipArray['gt15']['fluid']) ? $shipArray['gt15']['fluid'] : 0;//317;
            }else {
                $shipmentId = isset($shipArray['gt15']['other']) ? $shipArray['gt15']['other'] : 0;//28;
            }
        }else{
            if ($product_limit_type == '含电池')  {//带电
                $shipmentId = isset($shipArray['le15']['battery']) ? $shipArray['le15']['battery'] : 0;//273;
            }elseif ($product_limit_type == '含液体' || $product_limit_type == '含粉尘'){
                $shipmentId = isset($shipArray['le15']['fluid']) ? $shipArray['le15']['fluid'] : 0;//316;
            }else {
                $shipmentId = isset($shipArray['le15']['other']) ? $shipArray['le15']['other'] : 0;//291;
            }
        }
        return $shipmentId;
    }
    
    /**
     * 根据物流ID和重量计算运费
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getShipFee($id, $weight){
        $shipmentInfo = ZoneModel::where('logistics_id',$id)->first();
        if (empty($shipmentInfo)){
            return 0;
        }
        //$shipmentCalculateElementArray = unserialize($shipmentInfo['shipmentCalculateElementArray']);
        //运费 = 首重费用 + {[总重 - 首重] ÷ 续重} * 续重费用 + 操作费
        $firstFee         = $shipmentInfo->fixed_price;
        $firstWeight      = $shipmentInfo->fixed_weight;
        $additionalFee    = $shipmentInfo->continued_price;
        $additionalWeight = $shipmentInfo->continued_weight;
        $operateFee       = $shipmentInfo->price;
        $shipFee = $firstFee + ceil(($weight - $firstWeight) / $additionalWeight) * $additionalFee + $operateFee;
        return $shipFee;
    }
    
    /**
     * 根据ProductId获取erp中SKU列表
     * @param $productId
     * @return array
     */
    public function getLocalSmtSkuCodeBy($productId){
        $skuDataList = smtProductSku::where('productId',$productId)->get()->toArray();
        $return = array();
        if($skuDataList){
            foreach ($skuDataList as $skuItem){
                $return[] = strtoupper($skuItem['smtSkuCode']);
            }
            $return = array_unique($return);
        }
        return $return;
    }
    
    /**
     * 根据平台代码获取平台信息
     * @param  [type] $platType [description]
     * @return [type]           [description]
     */
    public function _getProductPlatInfoByPlatType($platType){
        $products_plat = $this->defineProductPublishPlatArray();
        foreach ($products_plat as $key => $value) {
            if($value['platType'] == $platType){
                return $value;
            }
        }
        return false;
    }
    
    private  function defineProductPublishPlatArray( )
    {
        $array = array(
            array(
                'platID' => '101',
                'platTitle' => 'eBay.us',
                'platType' => 'USD',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '102',
                'platTitle' => 'eBay.au',
                'platType' => 'AUD',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '103',
                'platTitle' => 'eBay.uk',
                'platType' => 'GBP',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '104',
                'platTitle' => 'eBay.de',
                'platType' => 'EUR',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '105',
                'platTitle' => 'eBay.fr',
                'platType' => 'EUR',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '106',
                'platTitle' => 'eBay.ca',
                'platType' => 'C',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '199',
                'platTitle' => 'eBay.other',
                'platType' => 'ebay.other',
                'platTypeID' => '1'
            ),
            array(
                'platID' => '201',
                'platTitle' => 'Amazon.de',
                'platType' => 'Amazon.de',
                'platTypeID' => '3'
            ),
            array(
                'platID' => '202',
                'platTitle' => 'Amazon.uk',
                'platType' => 'Amazon.uk',
                'platTypeID' => '3'
            ),
            array(
                'platID' => '203',
                'platTitle' => 'Amazon.us',
                'platType' => 'Amazon.us',
                'platTypeID' => '3'
            ),
            array(
                'platID' => '204',
                'platTitle' => 'Amazon.ca',
                'platType' => 'Amazon.ca',
                'platTypeID' => '3'
            ),
            array(
                'platID' => '205',
                'platTitle' => 'Amazon.fr',
                'platType' => 'Amazon.fr',
                'platTypeID' => '3'
            ),
            array(
                'platID' => '299',
                'platTitle' => 'Amazon.other',
                'platType' => 'Amazon.other',
                'platTypeID' => '3'
            ),
            array(
                'platID' => '301',
                'platTitle' => 'Aliexpress',
                'platType' => 'SMT',
                'platTypeID' => '6'
            ),
            array(
                'platID' => '401',
                'platTitle' => 'DHgate',
                'platType' => 'DHgate',
                'platTypeID' => '4'
            ),
            array(
                'platID' => '501',
                'platTitle' => '网站',
                'platType' => 'B2C',
                'platTypeID' => '5'
            )
        );
        return $array;
    }
    
    public function getExchangeRateByType($type = "RMB"){
        return CurrencyModel::where('code',$type)->first()->rate;
    }
    
    /**
     * 计算利润率
     * @param [type] $array [description]
     */
    public function _setProfitRate($productId, $sku, $price){  
        $product = ProductModel::where('model',$sku)->first();
        $products_value = 0;
        $products_weight = 0;
        $profitRate = 0;
        if($product){
            $products_value = $product->purchase_price;
            $products_weight = $product->weight;
            if(!empty($sku)) {
                if(count($product->logisticsLimit)){
                    $product_limit_type = $product->logisticsLimit->name;
                    if ($product_limit_type) {
                        $profitRate = $this->getProfitRate($price,$products_value, $products_weight, '',$product_limit_type);
                    }else{
                        $profitRate = NULL;
                    }
                }
            
            }
        }      
      
        return $profitRate;
    }
    
    /**
     * 速卖通计算利润率
     * @param integer $price 售价
     * @param string $cost  成本价
     * @param float $weight 重量
     * @param string $products_sort
     * @param unknown $product_limit_type  物流限制类型(是否含电、液体、粉尘)
     * @return number
     */
    public function getProfitRate($price=10, $cost = '6', $weight=0.02, $products_sort ='14324', $product_limit_type){
        $salePlat    = ErpSalesPlatform::where('platID',5)->first()->toArray();// 获取速卖通平台费率等信息
        $platFeeRate = $salePlat['platFeeRate'];
        $platFee = $salePlat['platOperateFee']; //平台操作费
        /**
         * 1).耳机(15052)、手表(14324): 北京平邮 (180)
         * 2).液体/粉末走马来西亚平邮 (175)
         * 3).成人用品(67588)，匹配到颐行香港平邮(178)
         * 4).M1账号,普货: 俄罗斯无锡(139); 带电: 荷兰小包六区(151);
         * 5).其它账号, 15美金以下,普货: SMT 颐行香港平邮(178), 带电: 荷兰小包六区(151); 15美金以上,普货: 俄罗斯无锡(139), 带电: 荷兰小包六区(151);
        */        

        if($price <= 15){               
            $shipMethod = 176;    //普货
            if( $product_limit_type == '含电池' ) $shipMethod = 188;
            if( $product_limit_type == '含液体' || $product_limit_type == '含粉尘' ) $shipMethod = 175;
            if( $products_sort == 15052 ) $shipMethod = 180;//耳机
            if( $products_sort == 176994 ) $shipMethod = 188;//点烟器电子烟类目    
        }else{// 15美金以上            
            $shipMethod = 139;  // 普货
            if( $product_limit_type == '含电池' ) $shipMethod = 151;
        }
      
        $shipFee = $this->getShipFee($shipMethod,$weight);
        // pFit【利润率】= (1- ( ((cost【成本价】 + shipFee【运费】) / exchangeRate【汇率1】 + platFee【固定费0】)/price + platFeeRate【费率5%】 / 100 ) ) * 100
        $exchangeRate = 1; // 速卖通是美元，汇率为1
        $price        = $price * $this->_CNYExchangeRate;
        // echo 'price:'.$price.'cost:'.$cost.'token_id:'.$token_id.'weight:'.$weight.'with_battery:'.$with_battery.'shipFee:'.$shipFee;
        $profitRate = (1- ( (($cost + $shipFee) / $exchangeRate + $platFee)/$price + $platFeeRate/100 ) ) * 100;
        return round($profitRate, 2);
    }
}
