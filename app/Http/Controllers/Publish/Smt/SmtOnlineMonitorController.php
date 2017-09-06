<?php

namespace App\Http\Controllers\Publish\Smt;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtProductSku;
use Illuminate\Support\Facades\Input;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductList;
use App\Models\ProductModel;
use App\Models\ItemModel;


class SmtOnlineMonitorController extends Controller
{
    public function __construct(smtProductSku $smtProductSku){
        $this->viewPath = "publish.smt.";  
        $this->model = $smtProductSku;
        $this->mainIndex = route('smtMonitor.index');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->mainTitle='速卖通在线数量监控';
        $list = $this->model->whereHas('product',function($query){
            $query = $query->whereIn('productStatusType',['onSelling','offline','auditing','editingRequired']);
        });
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$list),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'onlineMonitor', $response);
    }
    
    /**
     * 编辑商品单个SKU可售库存
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editSingleSkuStock(){
        $data = array();
        $data = Input::get();
        $account_id = $data['account_id'];
        if($data['ipmSkuStock'] < 1){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '可售库存设置不正确 !'));
        }
        $account = AccountModel::findOrFail($account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config); 
        $result = $channel->editSingleSkuStock($data);
        if(array_key_exists('success',$result) && $result['success']){
            $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
            $updateData = ['ipmSkuStock' => $data['ipmSkuStock'],'updated' => date('Y:m:d H:i:s',time())];
            $this->model->where($where)->update($updateData);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '产品'.$data['productId'].'的库存修改成功 !'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '库存修改失败:'.$result['error_message']));
        }
    }
    
    /**
     * 编辑商品单个SKU价格
     */
    public function editSingleSkuPrice(){
        $data = array();
        $data = Input::get();
        $account_id = $data['account_id'];
        $smtSkuCode = $data['smtSkuCode'];
        $setPriceType = $data['type'];
        $skuPrice = $data['skuPrice'];
        if(!$data['skuPrice']){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '价格不能为空 !'));
        }
        $account = AccountModel::findOrFail($account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $smtProductObj = new SmtProductsController();
        //获取商品的最新信息
        $last_product_Info = $channel->findAeProductById($data['productId']);
        if(array_key_exists('error_code', $last_product_Info)){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '获取商品信息失败:'.$last_product_Info['error_message']));
        }
        
        // 更新SKU的价格信息
        $aeopAeProductSKUs = array();
        $sku = is_array($smtSkuCode) ? $smtSkuCode : array($smtSkuCode);

        foreach($last_product_Info['aeopAeProductSKUs'] as $key => $v){
            if(in_array($v['skuCode'], $sku)){
                if($setPriceType == 'amount'){
                    $price = $v['skuPrice'] + $skuPrice;
                }else if($setPriceType == 'realprice'){
                    $price = $skuPrice;
                }else{
                    $price = $v['skuPrice'] * (1 + $skuPrice/100);
                }
                $v['skuPrice'] = sprintf('%.2f', round($price, 2));
                /*$skuCode = smtProductSku::where('smtSkuCode',$v['skuCode'])->first()->skuCode;
                if($skuCode){
                    $profitRate = $smtProductObj->_setProfitRate($data['productId'], $skuCode, $v['skuPrice']);
                }else {
                   $profitRate = 0; 
                }*/
            }
        }
        $data['skuPrice'] = $price;
    
        $result = $channel->editSingleSkuPrice($data);
        if(array_key_exists('success',$result) && $result['success']){
            $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
            $updateData = ['skuPrice' => $data['skuPrice'],'updated' => date('Y:m:d H:i:s',time())];
            $this->model->where($where)->update($updateData);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '产品'.$data['productId'].'的价格修改成功 !'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '产品'.$data['productId'].'的价格修改失败:'.$result['error_message']));
        }
    }
    
    /**
     * 手动更新商品信息
     * @return unknown
     */
    public function manualUpdateProductInfo(){
        $product_info_str = Input::get('productIds');        
        $productInfo = explode(' ', $product_info_str);
        $msg = '';
        foreach($productInfo as $val){
            list($productId,$smtSkuCode) = explode(',',$val); 
            $sku_info = smtProductSku::where(['productId'=>$productId,'smtSkuCode'=>$smtSkuCode,'isRemove'=>0])->first();                  
            if(strstr($sku_info->skuCode,'{YY}')){
                $sku_info->skuCode = substr($sku_info->skuCode,4);
            }else{
                continue;
            }
            $virtualStock = $sku_info->productItem ? $sku_info->productItem->available_quantity : 0;        //虚库存
            $product_info = ItemModel::where('sku',$sku_info->skuCode)->first();
            $account_id = $sku_info->product->token_id;
            $account = AccountModel::findOrFail($account_id);         
            $data = array();
            if($product_info){
                if($sku_info->product->productStatusType != 'onSelling'){               
                    $msg = 'SKU:'.$sku_info->skuCode.'已下架，操作失败!<br/>';
                    continue;
                }
                if($product_info->status == 'selling'){
                    if($sku_info->product->multiattribute == 0){
                        $targetStock = $virtualStock > 1 ? $virtualStock : 1;
                        $skuStocks[$sku_info->sku_active_id] = $targetStock;
                        
                        $data['skuStocks'] = json_decode($skuStocks);
                        $data['productId'] = $sku_info->productId;
                        $this->editSkuStocks($account,$data);                                        
                    }else{
                        $product_skus = smtProductSku::where('productId',$sku_info->productId)->get();
                        foreach($product_skus as $skuRow){
                            $sku = $this->changeSku($skuRow->skuCode);
                            $productType = $skuRow->product ? $skuRow->product->status : '';
                            if(empty($productType)){
                                continue;
                            }
                            
                            
                        }
                    }
                }
            }           
            
        }
    }
    
    /**
     * 过滤带{YY}了类型的SKU
     * @param unknown $sku
     */
    public function changeSku($sku){
        if (strstr($sku, "{YY}")) {
            $sku = substr(trim($sku),4);
        }
        return $sku;
    }
    
    /**
     * 批量处理商品上下架
     * @return multitype:multitype:boolean string
     */
    public function ajaxOperateOnlineProductStatus(){
        $product_id_str = Input::get('productIds');
        $type = Input::get('type');
        $productArr = explode(',', $product_id_str);
        $productIds = array_unique($productArr);
        $response = array();
        foreach ($productIds as $productId){
            $smtProduct = smtProductList::where('productId',$productId)->first();
            $account = AccountModel::findOrFail($smtProduct->token_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            if($type == 'online'){
                $api = 'api.onlineAeProduct';
            }elseif($type == 'offline'){
                $api = 'api.offlineAeProduct';
            }else{
                $this->ajax_return('操作失败!',0);
            }
            $productId = $smtProduct->productId;
            $result= $smtApi->updateProductPublishState($api,$productId);
            if(array_key_exists('success',$result) && $result['success']){
                if($type == 'online'){
                    $data['productStatusType'] = 'onSelling';
                }else{
                    $data['productStatusType'] = 'offline';
                }
                $this->model->where('productId',$productId)->update($data);
                $response[] = array('Status'=> true, 'Msg' => '设置成功');
            }else{
                $response[] = array('Status' => false, 'Msg' => '产品'.$productId.'操作失败!失败原因：'.$result['error_message']);
            }
        }
        return $response;
    }
    
    /**
     * 批量修改商品在线库存
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchEditSkuStock(){
        $product_info = Input::get('products');
        $impSkuStock = Input::get('impSkuStock');
        $arr = explode(' ', $product_info); 
        $product_ids = $this->formatProductArray($arr);
 
        foreach($product_ids as $value){
            $productId = $value['product_id'];
            $smtSkuCode = array_unique($value['sku']);
            $product_skus = $this->model->whereIn('smtSkuCode', $smtSkuCode)->get()->toArray();
            $skuStocks = array();
            foreach($product_skus as  $row){
                $skuStocks[$row['sku_active_id']] = $impSkuStock;
            }   
            
            //获取渠道帐号信息
            $token_id = smtProductList::where('productId',$productId)->first()->token_id;         
            $account = AccountModel::findOrFail($token_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            
            $data['skuStocks'] = json_encode($skuStocks);
            $data['productId'] = $productId;
            $api = 'api.editMutilpleSkuStocks';          
            $result = $channel->getJsonDataUsePostMethod($api,$data);         
            $result = json_decode($result,true);
            if(array_key_exists('success',$result) && $result['success']){
                foreach ($product_skus as $item){
                    $where = ['productId' => $data['productId'], 'sku_active_id' => $item['sku_active_id']];
                    $updateData = ['ipmSkuStock' => $impSkuStock];
                    $this->model->where($where)->update($updateData);
                }                
                $flag = true;
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '库存修改失败:'.$result['error_message']));
            }
        
        }                 
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success',  '批量操作成功 !'));
    }
    
    /**
     * 批量修改商品价格
     */
    public function batchEditSkuPrice(){
        $product_info = Input::get('products');
        $skuPrice = Input::get('skuPrice');
        $setPriceType = Input::get('type');
        $arr = explode(' ', $product_info);
        foreach ($arr as  $item){
            $singleSkuArr = explode(',', $item);
            $productId = $singleSkuArr[0];
            $smtSkuCode = $singleSkuArr[1];
            
            $skuInfo = $this->model->where('smtSkuCode',$smtSkuCode)->first();
            $account_id = $skuInfo->product->token_id;
            
            $account = AccountModel::findOrFail($account_id);
            
            
            //获取商品的最新信息
            $last_product_Info = $channel->findAeProductById($productId);
            if(array_key_exists('error_code', $last_product_Info)){
                return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '获取商品信息失败:'.$last_product_Info['error_message']));
            }
            
            // 更新SKU的价格信息
            $aeopAeProductSKUs = array();
            $sku = is_array($smtSkuCode) ? $smtSkuCode : array($smtSkuCode);
            
            foreach($last_product_Info['aeopAeProductSKUs'] as $key => $v){
                if(in_array($v['skuCode'], $sku)){
                    if($setPriceType == 'amount'){
                        $price = $v['skuPrice'] + $skuPrice;
                    }else if($setPriceType == 'realprice'){
                        $price = $skuPrice;
                    }else{
                        $price = $v['skuPrice'] * (1 + $skuPrice/100);
                    }
                    $v['skuPrice'] = sprintf('%.2f', round($price, 2));
                }
            }
            $data['skuPrice'] = $price;
            $data['skuId'] = $skuInfo->sku_active_id;
            $data['productId'] = $productId;
            $result = $channel->editSingleSkuPrice($data);
            if(array_key_exists('success',$result) && $result['success']){
                $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
                $updateData = ['skuPrice' => $data['skuPrice'],'updated' => date('Y:m:d H:i:s',time())];
                $this->model->where($where)->update($updateData);                
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '的价格修改失败:'.$result['error_message']));
            }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',  '操作成功 !'));
    }
    
    /**
     * 批量调有货/无货
     * @return multitype:boolean string multitype:boolean string
     */
    public function ajaxOperateProductSkuStockStatus(){
        $product_id_str = Input::get('productIds');
        $type = Input::get('type');
        if($type == 'set_sku_stock_true'){
            $isStock = 1;
            $stock_text = '有货';
        }else if($type == 'set_sku_stock_false'){
            $isStock = 0;
            $stock_text = '无货';
        }
        $productArr = explode(' ', $product_id_str);
        foreach($productArr as $product){
            list($productId,$smtSkuCode) = explode(',', $product);
            $skuInfo = $this->model->where('smtSkuCode',$smtSkuCode)->first();
            $account_id = $skuInfo->product->token_id;            
            $account = AccountModel::findOrFail($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            
            //获取商品最新信息
            $lastest_product_Info = $channel->findAeProductById($productId);
            if(array_key_exists('success', $lastest_product_Info) && $lastest_product_Info['success']){
                // 更新SKU的库存信息
                $aeopAeProductSKUs = array();
                $sku = is_array($smtSkuCode) ? $smtSkuCode : array($smtSkuCode); // 把SKU转成数组
                foreach ($lastest_product_Info['aeopAeProductSKUs'] as $key => $value) {
                    if (in_array($value['skuCode'], $sku)) {
                        $lastest_product_Info['aeopAeProductSKUs'][$key]['skuStock'] = $isStock;
                        $lastest_product_Info['aeopAeProductSKUs'][$key]['ipmSkuStock'] = ($isStock ? 999 : 0);
                    }
                }
                $result = $this->apiEditAeProduct($account,$lastest_product_Info);
                if(array_key_exists('success',$result) && $result['success']){
                    $where = ['productId' => $productId, 'smtSkuCode' =>$smtSkuCode];
                    $updateData = ['updated' => date('Y:m:d H:i:s',time()),'skuStock' => $isStock];
                    $this->model->where($where)->update($updateData);
                    $response[] = array('Status'=> true, 'Msg' => $productId.'['.$sku.']调'.$stock_text.'成功! ');
                }else{
                    $response[] = array('Status' => false, 'Msg' => '产品'.$productId.'['.$sku.']调'.$stock_text.'失败原因：'.$result['error_message']);
                }
            }                  
        }
        return $response;
    }
    
    /**
     * 对传入的数组进行格式化
     */
    public function formatProductArray($product_id){
        foreach ($product_id as $value) {
            list($v[0], $v[1]) = explode(',', $value);
            $p_ids[$v[0]][] = $v[1];
        }
        foreach ($p_ids as $key => $value) {
            $product_ids[] = array('product_id' => $key, 'sku' => $value);
        }
        
        return $product_ids;
    }
    
    public function editSkuStocks(AccountModel $account,$data){
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $api = 'api.editMutilpleSkuStocks';
        $result = $channel->getJsonDataUsePostMethod($api,$data);
        $result = json_decode($result,true);
        if(array_key_exists('success', $result) && $result['success'] && $result['modifyCount'] > 0){
            smtProductSku::where(['productId'=>$data['productId'],'sku_active_id'=>$data['sku_active_id']])->update(['ipmSkuStock'=>$data['$data']]);
        }
       
    }  
    
    /**
     * 组装产品信息，修改SKU的库存状态
     * @param AccountModel $accountModel
     * @param unknown $product_info
     * @return mixed
     */
    public function apiEditAeProduct(AccountModel $accountModel,$product_info){
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $arr['productId']              = $product_info['productId'];
        $arr['subject']                = $product_info['subject'];
        $arr['categoryId']             = $product_info['categoryId'];
        $arr['detail']                 = $product_info['detail'];
        $arr['deliveryTime']           = $product_info['deliveryTime'];
        $arr['productPrice']           = $product_info['productPrice'];
        $arr['freightTemplateId']      = $product_info['freightTemplateId'];
        $arr['isImageDynamic']         = $product_info['isImageDynamic'] == 1 ? 'true' : 'false';
        $arr['imageURLs']              = $product_info['imageURLs'];
        $arr['productUnit']            = $product_info['productUnit'];
        $arr['packageType']            = ($product_info['packageType'] ? 'true' : 'false');
        $arr['packageLength']          = $product_info['packageLength'];
        $arr['packageWidth']           = $product_info['packageWidth'];
        $arr['packageHeight']          = $product_info['packageHeight'];
        $arr['grossWeight']            = $product_info['grossWeight'];
        $arr['wsValidNum']             = $product_info['wsValidNum'];
        $arr['isPackSell']             = $product_info['isPackSell'];//新增必填字段
        if($arr['packageType'])
        {
            $arr['lotNum']   = $product_info['lotNum'];
        }
        $arr['currencyCode']           = $product_info['currencyCode'];
        $arr['aeopAeProductSKUs']      = json_encode( $product_info['aeopAeProductSKUs']);
        $arr['aeopAeProductPropertys'] = json_encode($product_info['aeopAeProductPropertys']);
        $result = $channel->getJsonDataUsePostMethod( "api.editAeProduct", $arr);
        return json_decode($result,true);
    }
   
}
