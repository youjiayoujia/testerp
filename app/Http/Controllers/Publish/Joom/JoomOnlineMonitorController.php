<?php
/*Time:2016-10-4
 *Joom数据
 *user:hejiancheng
 */

namespace App\Http\Controllers\Publish\Joom;
ini_set('memory_limit','256M');
use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Joom\JoomPublishProductDetailModel;
use App\Models\StockModel;
use App\Models\Channel\AccountModel;

class JoomOnlineMonitorController extends Controller
{
   public function __construct(){
      $this->mainIndex = route('joomonline.index');
      $this->mainTitle = 'Joom在线数量监控';
      $this->model = new JoomPublishProductDetailModel();
      $this->viewPath = 'publish.joom.onlineMonitor.';
   }
    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'stocks' => $this->getRealQuantity(),
        ];
        $response['mixedSearchFields']=[
            'filterSelects' => [
                    'enabled' => [
                        1=>'上架',
                        0=>'已下架',
                ]
            ],
            'filterFields' => [
                'joom_publish_product_detail.productID',
                'joom_publish_product_detail.sku',
                'erp_sku',
            ],
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
    
    /*Time:2016-10-4
     * 修改joom_sku在线库存
     * @parameter:$joom_sku $token
     */
    public function setSellerinventory(){
        set_time_limit ( 0 );
        $error = '';
        $sellerSku[] = request()->input('sku');
        $Quantity = request()->input('Quantity');
        if(empty($Quantity)){   //empty return
            return redirect($this->mainIndex)->with('alert', $this->alert('error','在线数量不能为空!'));
        }
        if(!is_numeric($Quantity)){   //not number return
            return redirect($this->mainIndex)->with('alert', $this->alert('error','在线数量包含非数字字符，请检查后提交!'));
        }
        foreach($sellerSku as $s_k=>$s_v){
            $res = $this->model->where("sku", $s_v)->first();
            $account = AccountModel::find($res->account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $re = $channel->changejoomProductCount($s_v,$Quantity);
            if(isset($re['code']) && $re['code']==0){
                $error .= 'sku【' . $s_v . '】在线库存修改成功';
                $data = array();
                $data['inventory'] = $Quantity;
                JoomPublishProductDetailModel::where('sku',$s_v)->update($data);  //success,update sku inventory
              }else{
                $error .= 'sku【' . $s_v . '】在线库存修改失败，原因是'.$re['message'].'';
            }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',$error));
    }

    /*Time:2016-10-4
      * 修改joom_sku运费
      * @parameter:$joom_sku $token
      */
    public function setshipping(){
        set_time_limit ( 0 );
        $error = '';
        $sellerSku[] = request()->input('sku');
        $shipping = request()->input('shipping');
        if(empty($shipping)){   //empty return
            return redirect($this->mainIndex)->with('alert', $this->alert('error','运费不能为空!'));
        }
        if(!is_numeric($shipping)){   //not number return
            return redirect($this->mainIndex)->with('alert', $this->alert('error','运费包含非数字字符，请检查后提交!'));
        }

        foreach($sellerSku as $s_k=>$s_v){
            $res = $this->model->where("sku", $s_v)->first();
            $account = AccountModel::find($res->account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $re = $channel->changeProductShipping($s_v,$shipping);
            if(isset($re['code']) && $re['code']==0){
                $error .= 'sku【' . $s_v . '】运费修改成功';
                $data = array();
                $data['shipping'] = $shipping;
                JoomPublishProductDetailModel::where('sku',$s_v)->update($data);  //success,update sku inventory
            }else{
                $error .= 'sku【' . $s_v . '】运费修改失败，原因是'.$re['message'].'';
            }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',$error));
    }
    /*Time:2016-10-4
      * 修改joom_sku价格 批量和单个  百分比修改
      * @parameter:$joom_sku $token
      */
    public function setPrice(){
        set_time_limit ( 0 );
        $error = '';
        $sellerSku[] = request()->input('sku');
        $price = request()->input('price');
        if(empty($price)){   //empty return
            return redirect($this->mainIndex)->with('alert', $this->alert('error','价格不能为空!'));
        }
        if(!is_numeric($price)){   //not number return
            return redirect($this->mainIndex)->with('alert', $this->alert('error','价格包含非数字字符，请检查后提交!'));
        }
        foreach($sellerSku as $s_k=>$s_v){
            $res = $this->model->where("sku", $s_v)->first();
            $account = AccountModel::find($res->account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $re = $channel->changejoomProductPrice($s_v,$price);
            if(isset($re['code']) && $re['code']==0){
                $error .= 'sku【' . $s_v . '】价格修改成功';
                $data = array();
                $data['price'] = $price;
                JoomPublishProductDetailModel::where('sku',$s_v)->update($data);  //success,update sku price
            }else{
                $error .= 'sku【' . $s_v . '】价格修改失败，原因是'.$re['message'].'';
            }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',$error));
    }
    /*Time:2016-10-4
      * 修改joom_sku状态  上架 下架
      * @parameter:$joom_sku $token
      */
    public function setstatus(){
        set_time_limit ( 0 );
        $info = '';
        $sellerSku[] = request()->input('sku');
        $status = request()->input('status');
        $type = 'error';
        $enabled = 'error';
        $str = '';
        if($status == 0){
            $type = '1';
            $enabled = 'enable';
            $str = '上架';
        }else if($status == 1){
            $type = '0';
            $enabled = 'disable';
            $str = '下架';
        }
        if(!$sellerSku || $type == 'error' || $enabled == 'error'){
              $info = array('status' => 'error','info' => 'sku不能为空或发生错误！');
        }else{
            foreach($sellerSku as $s_k=>$s_v){
                $res = $this->model->where("sku", $s_v)->first();
                $account = AccountModel::find($res->account_id);
                $channel = Channel::driver($account->channel->driver, $account->api_config);
                $re = $channel->changeProductStatusbySku($s_v,$enabled);
                if(isset($re['code']) && $re['code']==0){
                    $info = array('status' => 'success','info' => 'sku【' . $s_v . '】'.$str.'成功');
                    $data = array();
                    $data['enabled'] = $type;
                    JoomPublishProductDetailModel::where('sku',$s_v)->update($data);  //success,update sku enabled
                }else{
                    $info = array('status' => 'success','info' => 'sku【' . $s_v . '】'.$str.'失败，原因是'.$re['message'].'');
                }
            }
        }
        echo json_encode($info);exit;
    }
    /*
     *Time:2016-10-05
     * Joom批量操作
     */
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
    /*Time:2016-10-6
     * Joom批量操作
     * @$products_ids $operate
     */
    public function batchUpdate(){
        set_time_limit ( 0 );
        $product_ids = request()->input("product_ids");      
        $arr = explode(',', $product_ids);
        $operate = request()->input("operate");
        $enabled = 'error';
        $type = 'error';
        $str = '';
        if($operate == 'changeup'){          //up
            $operate = 'changeStatus';
            $type = 1;
            $enabled = 'enable';
            $str = '上架';
        }else if($operate == 'changedown'){  //down
            $operate = 'changeStatus';
            $type = 0;
            $enabled = 'disable';
            $str = '下架';
        }
        $string = '';
        $res = $this->model->whereIn("id", $arr)->get();
        switch ($operate) {
            case 'changeQuantity';              //update Quantity
                $quantityArr = request()->input('quantity');
                foreach($res as $product){
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $sellerSku = $product->sku;
                    $count['Quantity'] = $quantityArr[$product->id];
                    if(empty($count['Quantity'])){   //empty return
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','在线数量不能为空!'));
                    }
                    if(!is_numeric($count['Quantity'])){   //not number return
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','在线数量包含非数字字符，请检查后提交!'));
                    }
                        $re = $channel->changejoomProductCount($sellerSku,$count['Quantity']);
                        if(isset($re['code']) && $re['code']==0){
                            $string .= 'sku【' . $sellerSku . '】在线库存修改成功 ';
                            $data = array();
                            $data['inventory'] = $count['Quantity'];
                            JoomPublishProductDetailModel::where('sku',$sellerSku)->update($data);  //success,update sku inventory
                        }else{
                            $string .= 'sku【' . $sellerSku . '】在线库存修改失败，原因是'.$re['message'].' ';
                        }
                }
                break;
            case 'changeStatus';     //update status
                foreach($res as $product){
                    $sellerSku = $product->sku;//var_dump($sellerSku);var_dump($type);var_dump($enabled);exit;
                    if(!$sellerSku || $enabled == 'error'){
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','sku不能为空或发生错误!'));
                    }else{
                        $account = AccountModel::find($product->account_id);
                        $channel = Channel::driver($account->channel->driver, $account->api_config);
                            $re = $channel->changeProductStatusbySku($sellerSku,$enabled);
                            if(isset($re['code']) && $re['code']==0){
                                $string .= 'sku【' . $sellerSku . '】'.$str.'成功！';
                                $data = array();
                                $data['enabled'] = $type;
                                JoomPublishProductDetailModel::where('sku',$sellerSku)->update($data);  //success,update sku enabled
                            }else{
                                $string .= 'sku【' . $sellerSku . '】'.$str.'失败，原因是'.$re['message'].' ';
                        }
                    }
                }
                break;
            
            case 'changePrice';        //update price
                $statusArr = request()->input('price');
                foreach($res as $product){
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $sellerSku = $product->sku;
                    $count['Price'] = $statusArr[$product->id];
                    if(empty($count['Price'])){   //empty return
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','价格不能为空!'));
                    }
                    if(!is_numeric($count['Price'])){   //not number return
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','价格包含非数字字符，请检查后提交!'));
                    }
                    $re = $channel->changejoomProductPrice($sellerSku,$count['Price']);
                    if(isset($re['code']) && $re['code']==0){
                        $string .= 'sku【' . $sellerSku . '】价格修改成功';
                        $data = array();
                        $data['price'] = $count['Price'];
                        JoomPublishProductDetailModel::where('sku',$sellerSku)->update($data);  //success,update sku price
                    }else{
                        $string .= 'sku【' . $sellerSku . '】价格修改失败，原因是'.$re['message'].'';
                    }
                }
            break;
            case 'changeshipping';      //update shipping
                $saleshipping = request()->input('saleshipping');
                foreach($res as $product){
                    $sellerSku = $product->sku;
                    $count['shipping'] = $saleshipping[$product->id];
                    if(empty($count['shipping'])){   //empty return
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','运费不能为空!'));
                    }
                    if(!is_numeric($count['shipping'])){   //not number return
                        return redirect($this->mainIndex)->with('alert', $this->alert('error','运费包含非数字字符，请检查后提交!'));
                    }
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                        $re = $channel->changeProductShipping($sellerSku,$count['shipping']);
                        if(isset($re['code']) && $re['code']==0){
                            $string .= 'sku【' . $sellerSku . '】运费修改成功';
                            $data = array();
                            $data['shipping'] = $count['shipping'];
                            JoomPublishProductDetailModel::where('sku',$sellerSku)->update($data);  //success,update sku inventory
                        }else{
                            $string .= 'sku【' . $sellerSku . '】运费修改失败，原因是'.$re['message'].'';
                        }
                }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',$string));
    }
}
