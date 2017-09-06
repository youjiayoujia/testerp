<?php

namespace App\Http\Controllers\Publish\Lazada;
ini_set('memory_limit','256M');
use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Lazada\erpLazadaProduct;
use App\Models\StockModel;
use App\Models\Channel\AccountModel;

class LazadaOnlineMonitorController extends Controller
{
   public function __construct(){
      $this->mainIndex = route('lazada.index');
      $this->mainTitle = 'LAZADA在线数量监控';
      $this->model = new erpLazadaProduct();
      $this->viewPath = 'publish.lazada.onlineMonitor.';
   }
    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'stocks' => $this->getRealQuantity(),
        ];    

        return view($this->viewPath . 'index', $response);
    }
    
    /**
     * 获取产品的实库存
     * @return array | null
     */
    public function getRealQuantity(){
        $stocks = StockModel::all();
        $stockArr = array();
        if($stocks){
            foreach($stocks as $result){            
                @$stockArr[$result->item_id] += $result->all_quantity;  //同一SKU的不同仓位的实库存要累加
            }
        }

        return $stockArr;
    }
    
    /**
     * 设置商品的在线库存
     */
    public function setQuantity(){
        set_time_limit ( 0 );
        $string = '';
        $sellerSku[] = request()->input('sellerSku');
        $account_name = request()->input('account'); 
        $count['Quantity'] = trim(request()->input('quantity'));
        if(empty($count['Quantity'])){
            return redirect($this->mainIndex)->with('alert', $this->alert('error','在线数量不能为空!'));
        }
        $account = AccountModel::where('account',$account_name)->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config); 
        $result = $channel->operateProduct($sellerSku,1,$count);
        if(isset($result['Head']['RequestId'])){
            $RequestId = $result['Head']['RequestId'];
            for($i=1;$i>0;$i++) {
                $re = $channel->getFeedInfo();
                $feedinfo = $re['Body']['Feed'];
                foreach ($feedinfo as $k => $feed) {
                    if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                        break;
                    if ($feed['Feed'] == $RequestId) {
                        if ($feed['Status'] == 'Finished') {
                            if ($feed['FailedRecords'] == 0) {
                                $type = 1;
                                $string .='更新成功';
                            } else {
                                $type = 1;
                                $string .= '更新失败';
                            }
                        }
                    }
                }
                if ($type == 1)
                    break;
            }
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
        }
        if($type == 1){
            $data = array();
            $data['quantity'] = request()->input('quantity');
            erpLazadaProduct::where('sellerSku',$sellerSku[0])->update($data);
        }
       
        return redirect($this->mainIndex)->with('alert', $this->alert('success',$string));
    }
    
    /**
     * 修改单个sellSku的普通价格
     */
    public function setPrice(){
        set_time_limit ( 0 );
        $string = '';
        $sellerSku[] = request()->input('sellerSku');
        $account_name = request()->input('account');
        $count['Price'] = trim(request()->input('price'));
        if(empty($count['Price'])){
            return redirect($this->mainIndex)->with('alert', $this->alert('error','Sellersku价格不能为空!'));
        }
        $account = AccountModel::where('account',$account_name)->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->operateProduct($sellerSku,2,$count);
        if(isset($result['Head']['RequestId'])){
            $RequestId = $result['Head']['RequestId'];
            for($i=1;$i>0;$i++) {
                $re = $channel->getFeedInfo();
                $feedinfo = $re['Body']['Feed'];
                foreach ($feedinfo as $k => $feed) {
                    if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                        break;
                    if ($feed['Feed'] == $RequestId) {
                        if ($feed['Status'] == 'Finished') {
                            if ($feed['FailedRecords'] == 0) {
                                $type = 1;
                                $string .='更新成功';
                            } else {
                                $type = 2;
                                $string .= '更新失败';
                            }
                        }
                    }
                }
                if (($type == 1)||($type == 2))
                    break;
            }
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
        }
        if($type == 1){
            $data = array();
            $data['price'] = request()->input('price');
            erpLazadaProduct::where('sellerSku',$sellerSku[0])->update($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',$string));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error',$sellerSku[0].'的在线价格更改失败!'));
        }        
    }
    
    /**
     * 修改单个sellSku的销售价格
     */
    public function setSalePrice(){
        set_time_limit ( 0 );
        $string = '';
        $sellerSku[] = request()->input('sellerSku');
        $account_name = request()->input('account');
        $count['SalePrice'] = trim(request()->input('salePrice'));
        $count['Price'] = trim(request()->input('price'));
        $count['StartDate'] = request()->input('saleStartDate');
        $count['EndDate'] = request()->input('saleEndDate');
        if(empty($count['SalePrice'])){
            return redirect($this->mainIndex)->with('alert', $this->alert('error','Sellersku销售价格不能为空!'));
        }
        $account = AccountModel::where('account',$account_name)->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->operateProduct($sellerSku,4,$count);
        $type = 0;
        if(isset($result['Head']['RequestId'])){
            $RequestId = $result['Head']['RequestId'];
            for($i=1;$i>0;$i++) {
                $re = $channel->getFeedInfo();
                $feedinfo = $re['Body']['Feed'];
                foreach ($feedinfo as $k => $feed) {
                    if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                        break;
                    if ($feed['Feed'] == $RequestId) {
                        if ($feed['Status'] == 'Finished') {
                            if ($feed['FailedRecords'] == 0) {
                                $type = 1;
                                $string .='更新成功';
                            } else {
                                $type = 2;
                                $string .= '更新失败,可能是因为Sale price must be lower than the standard price. 或者 结束时间比开始时间小';
                            }
                        }
                    }
                }
                if (($type == 1)||($type == 2))
                    break;
            }
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
        }
        if($type == 1){
            $data = array();
            $data['salePrice'] = trim(request()->input('salePrice'));
            erpLazadaProduct::where('sellerSku',$sellerSku[0])->update($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',$string));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error',$sellerSku[0].'的销售价格更改失败!'));
        }
    }
    
    /**
     * 修改单个sellSku在线状态
     */
    public function setSellerSkuStatus(){
        set_time_limit ( 0 );
        $string = '';
        $sellerSku[] = request()->input('sellerSku');
        $status = request()->input('status');
        $account_name = request()->input('account');
        $count['Status'] = trim(request()->input('skuStatus'));
        if($count['Status'] == $status){
            return redirect($this->mainIndex)->with('alert', $this->alert('error','Sellersku的状态不能和目前相同！!'));
        }
        $account = AccountModel::where('account',$account_name)->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->operateProduct($sellerSku,3,$count);
        if(isset($result['Head']['RequestId'])){
            $RequestId = $result['Head']['RequestId'];
            for($i=1;$i>0;$i++) {
                $re = $channel->getFeedInfo();
                $feedinfo = $re['Body']['Feed'];
                foreach ($feedinfo as $k => $feed) {
                    if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                        break;
                    if ($feed['Feed'] == $RequestId) {
                        if ($feed['Status'] == 'Finished') {
                            if ($feed['FailedRecords'] == 0) {
                                $type = 1;
                                $string .='更新成功';
                            } else {
                                $type = 2;
                                $string .= '更新失败';
                            }
                        }
                    }
                }
                if (($type == 1)||($type == 2))
                    break;
            }
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
        }
        if($type == 1){
            $data = array();
            $data['status'] = request()->input('skuStatus');
            erpLazadaProduct::where('sellerSku',$sellerSku[0])->update($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',$string));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('error',$sellerSku[0].'的在线状态更改失败!'));
        }
    }
    
    public function productBatchEdit()
    {
        $ids = request()->input("ids");
        $arr = explode(',', $ids);
        $param = request()->input('param');
        $products = $this->model->whereIn("id", $arr)->orderBy('id', 'desc')->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'products' => $products,
            'product_ids' => $ids,
            'param' => $param,
        ];
        return view($this->viewPath . 'batchEdit', $response);
    
    }
    
    public function batchUpdate(){
        set_time_limit ( 0 );
        $product_ids = request()->input("product_ids");      
        $arr = explode(',', $product_ids);
        $operate = request()->input("operate");
        $string = '';
        $res = $this->model->whereIn("id", $arr)->get();
        switch ($operate) {
            case 'changeQuantity';                
                $quantityArr = request()->input('quantity');
                foreach($res as $product){
                    $account = AccountModel::where('account',$product->account)->first();
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $sellerSku = $product->sellerSku;                    
                    $count['Quantity'] = $quantityArr[$product->id];
                    $result = $channel->operateProduct($sellerSku,1,$count);
                    $type=0;
                    if(isset($result['Head']['RequestId'])){
                        $RequestId = $result['Head']['RequestId'];
                        for($i=1;$i>0;$i++) {
                            $re = $channel->getFeedInfo();
                            $feedinfo = $re['Body']['Feed'];
                            foreach ($feedinfo as $k => $feed) {
                                if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                                    break;
                                if ($feed['Feed'] == $RequestId) {
                                    if ($feed['Status'] == 'Finished') {
                                        if ($feed['FailedRecords'] == 0) {
                                            $type = 1;
                                            break;
                                        } 
                                    }
                                }
                            }
                            if ($type == 1) break;                                
                        }
                    }else{
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
                    }
                    if($type == 1){
                        $data = array();
                        $data['quantity'] = $count['Quantity'];
                        erpLazadaProduct::where('sellerSku',$sellerSku)->update($data);
                        $string .= 'sellerSku为'.$sellerSku.'的在线数量调整成功!   ';
                    }
                }
                break;
            
            case 'changeStatus';
                $statusArr = request()->input('status');
                foreach($res as $product){
                    $account = AccountModel::where('account',$product->account)->first();
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $sellerSku = $product->sellerSku;                    
                    $count['Status'] = $statusArr[$product->id];
                    $type = 0;
                    $success = 0;
                    for($i=0;$i<50;$i++){
                        $result = $channel->operateProduct($sellerSku,3,$count);
                        if(isset($result['Head']['RequestId'])){
                            break;
                        }else{
                            sleep(10);
                        }
                        
                        if($i == 50){
                            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
                        }                      
                    }
                    $RequestId = $result['Head']['RequestId'];
                    for($j=1;$j<50;$j++){
                        $re = $channel->getFeedInfo();
                        $feedinfo = $re['Body']['Feed'];
                        foreach ($feedinfo as $k => $feed) {
                            if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                                break;
                            if ($feed['Feed'] == $RequestId) {
                                if ($feed['Status'] == 'Finished') {
                                    if ($feed['FailedRecords'] == 0) {
                                        $type = 1;
                                        $success = 1;
                                        break;
                                    } 
                                }
                            }
                        }
                        if ($type == 1)
                            break;
                        }
                        if($success == 1){
                            $data = array();
                            $data['status'] = $count['Status'] ;
                            erpLazadaProduct::where('sellerSku',$sellerSku)->update($data);
                            $string .= 'sellerSku为'.$sellerSku.'的在线状态调整成功!';
                        }
                    }
                break;
            
            case 'changePrice';
                $statusArr = request()->input('price');
                foreach($res as $product){
                    $account = AccountModel::where('account',$product->account)->first();
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $sellerSku = $product->sellerSku;
                    $count['Price'] = $statusArr[$product->id];
                    $type = 0;
                    $success = 0;
                    for($i=0;$i<50;$i++){
                        $result = $channel->operateProduct($sellerSku,2,$count);
                        if(isset($result['Head']['RequestId'])){
                            break;
                        }else{
                            sleep(10);
                        }
                
                        if($i == 50){
                            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
                        }
                    }
                    $RequestId = $result['Head']['RequestId'];
                    for($j=1;$j<50;$j++){
                        $re = $channel->getFeedInfo();
                        $feedinfo = $re['Body']['Feed'];
                        foreach ($feedinfo as $k => $feed) {
                            if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                                break;
                            if ($feed['Feed'] == $RequestId) {
                                if ($feed['Status'] == 'Finished') {
                                    if ($feed['FailedRecords'] == 0) {
                                        $type = 1;
                                        $success = 1;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($type == 1)
                            break;
                    }
                    if($success == 1){
                        $data = array();
                        $data['price'] = $count['Price'] ;
                        erpLazadaProduct::where('sellerSku',$sellerSku)->update($data);
                        $string .= 'sellerSku为'.$sellerSku.'的普通价格调整成功!';
                    }
                }
            break;
            case 'changeSalePrice';
                $statusArr = request()->input('salePrice');
                foreach($res as $product){
                    $account = AccountModel::where('account',$product->account)->first();
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $sellerSku = $product->sellerSku;
                    $count['SalePrice'] = $statusArr[$product->id];
                    $count['Price'] = $product->price;
                    $count['StartDate'] = $product->saleStartDate;
                    $count['EndDate'] = $product->saleEndDate;
                    $type = 0;
                    $success = 0;
                    for($i=0;$i<50;$i++){
                        $result = $channel->operateProduct($sellerSku,4,$count);
                        if(isset($result['Head']['RequestId'])){
                            break;
                        }else{
                            sleep(10);
                        }
                
                        if($i == 50){
                            return redirect($this->mainIndex)->with('alert', $this->alert('error','发送请求失败，可能请求太频繁!'));
                        }
                    }
                    $RequestId = $result['Head']['RequestId'];
                    for($j=1;$j<50;$j++){
                        $re = $channel->getFeedInfo();
                        $feedinfo = $re['Body']['Feed'];
                        foreach ($feedinfo as $k => $feed) {
                            if($k >10 ) // 单Feed的时候 一般改Feed处于第一个，没有必要循环全部、
                                break;
                            if ($feed['Feed'] == $RequestId) {
                                if ($feed['Status'] == 'Finished') {
                                    if ($feed['FailedRecords'] == 0) {
                                        $type = 1;
                                        $success = 1;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($type == 1)
                            break;
                    }
                    if($success == 1){
                        $data = array();
                        $data['salePrice'] = $count['SalePrice'] ;
                        erpLazadaProduct::where('sellerSku',$sellerSku)->update($data);
                        $string .= $sellerSku.'的销售价格调整成功!';
                    }
                }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',$string));
    }
}
