<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-08-19
 * Time: 15:45
 */
namespace App\Http\Controllers\Publish\Ebay;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\PaypalsModel;
use App\Models\Publish\Ebay\EbaySellerCodeModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\Publish\Ebay\EbayReplenishmentLogModel;

class EbayDataMonitorController extends Controller
{
    public function __construct(EbayPublishProductModel $ebayProduct, EbayPublishProductDetailModel $ebayProductDetail,EbaySellerCodeModel $sellerCode, EbaySiteModel $ebaySite ,PaypalsModel $payPal)
    {
        $this->model = $ebayProduct;
        $this->mainIndex = route('ebayProduct.index');
        $this->mainTitle = 'Ebay数据监控';
        $this->viewPath = 'publish.ebay.monitor.';
        $this->modelDetail = $ebayProductDetail;
        $this->sellerCode = $sellerCode;
        $this->ebaySite = $ebaySite;
        $this->payPal = $payPal;

    }

    public function index()
    {
        request()->flash();
        $list = $this->modelDetail->where('status', 1);
        $list = $list->whereHas('ebayProduct', function ($query) {
            $query = $query->where('status', 2);
        });
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->modelDetail, $list),
            'mixedSearchFields' => $this->modelDetail->mixed_search,
        ];
        $response['mixedSearchFields']=[
            'selectRelatedSearchs' => [
                'ebayProduct' =>[
                    'account_id' => $this->model->getChannelAccount(4),
                    'site_name' => $this->ebaySite->getSite(),
                    'paypal_email_address' =>$this->payPal->getPayPal('paypal_email_address'),
                    'currency' => $this->ebaySite->getSite('currency','currency'),
                    'listing_type'=> [
                        'Chinese'=>'Chinese',
                        'FixedPriceItem'=>'FixedPriceItem'
                    ],
                    'multi_attribute' => [
                        1=>'是',
                        0=>'否',
                    ],
                ],
                'erpProduct'=>[
                    'status'=>config('item.status')
                ]

            ],
            'filterSelects' => [
                'seller_id' =>$this->sellerCode->getEbayCodeWithName(),
            ],
            'filterFields' => [
                'item_id',
                'sku',
                'erp_sku',
            ],
            'sectionSelect' => [
                'price' => ['start_price','quantity_sold'],
                'time' =>['start_time']
            ],
//            'relatedSearchFields' => [
//                 'details'=>[
//                     'erp_sku'
//                 ]
//            ],
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function productBatchEdit()
    {
        $ids = request()->input("ids");
        $arr = explode(',', $ids);
        $param = request()->input('param');
        $products = $this->modelDetail->whereIn("id", $arr)->orderBy('item_id', 'desc')->get();
        $paypal_list = $this->payPal->getPayPal();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'products' => $products,
            'product_ids' => $ids,
            'param' => $param,
            'paypal' => $paypal_list
        ];
        return view($this->viewPath . 'batchEdit', $response);

    }

    /** 批量操作
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchUpdate()
    {
        $product_ids = request()->input("product_ids");
        $arr = explode(',', $product_ids);
        $operate = request()->input("operate");
        $string = '';
        switch ($operate) {
            case 'changeOutOfStock';
                $list = $this->model;
                $result = $list->whereHas('details', function ($query) use ($arr) {
                    $query = $query->whereIn('id', $arr);
                })->get();
                $is_out_stock = request()->input("outStock");
                foreach ($result as $product) {
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $api_result = $channel->changeOutOfStock($product->item_id, $is_out_stock,$product->site);
                    if ($api_result['status']) {
                        if ($is_out_stock == 'true')
                            $value = 1;
                        else
                            $value = 0;
                        $product->update(array('is_out_control' => $value));
                        $string .= $product->item_id .$api_result['info'];
                    } else {
                        $string .= $product->item_id . '更新失败:' . $api_result['info'];
                    }
                }
                break;
            case 'changeItemQuantity';
                $item_arr = [];
                $quantity = request()->input("quantity");
                $result = $this->modelDetail->whereIn('id', $arr)->get();
                foreach ($result as $v) {
                    $item_arr[$v->item_id][$v->sku] = $quantity['id'][$v->id];
                }
                foreach ($item_arr as $item_id => $item) {
                    $product = $this->model->where('item_id', $item_id)->first();
                    $is_mul = count($product->details) > 1 ? 1 : 0;
                    $i = 1;
                    $sku = [];
                    $sku_string = '';
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    foreach ($item as $key => $v) {
                        $sku_string .=' '.$key;
                        $sku[$key] = $v;
                        if ($i == 4) { //一次只能4个。。 changeQuantity
                            $api_result = $channel->changeQuantity($item_id, $sku, $is_mul,$product->site);
                            if ($api_result['status']) {
                                foreach($sku as $k => $num ){
                                    $this->modelDetail->where('item_id',$product->item_id)->where('sku',$k)->update(array('quantity'=>$num));
                                }
                                $string .= $product->item_id . $sku_string.  $api_result['info'];
                            }else{
                                $string .= $product->item_id . '更新失败:' . $api_result['info'];
                            }
                            $sku = [];
                            $i = 1;
                            $sku_string = '';
                        } else {
                            $i++;
                        }
                    }
                    if (!empty($sku)) {
                        $api_result = $channel->changeQuantity($item_id, $sku, $is_mul,$product->site);
                        if ($api_result['status']) {
                            foreach($sku as $k => $num ){
                                $this->modelDetail->where('item_id',$product->item_id)->where('sku',$k)->update(array('quantity'=>$num));
                                if(!$is_mul){
                                    $product->update(array('quantity'=>$num));
                                }
                            }
                            $string .= $product->item_id . $sku_string. $api_result['info'];
                        }else{
                            $string .= $product->item_id . '更新失败:' . $api_result['info'];
                        }
                    }
                }
                break;
            case 'changePrice';
                $item_arr = [];
                $start_price = request()->input("start_price");
                $result = $this->modelDetail->whereIn('id', $arr)->get();
                foreach ($result as $v) {
                    $item_arr[$v->item_id][$v->sku] = $start_price['id'][$v->id];
                }
                foreach ($item_arr as $item_id => $item) {
                    $product = $this->model->where('item_id', $item_id)->first();
                    $is_mul = count($product->details) > 1 ? 1 : 0;
                    $i = 1;
                    $sku = [];
                    $sku_string = '';
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    foreach ($item as $key => $v) {
                        $sku[$key] = $v;
                        if ($i == 4) { //一次只能4个。。 changeQuantity
                            $api_result = $channel->changePrice($item_id, $sku, $is_mul,$product->site);
                            if ($api_result['status']) {
                                foreach($sku as $k => $price ){
                                    $this->modelDetail->where('item_id',$product->item_id)->where('sku',$k)->update(array('start_price'=>$price));
                                    if(!$is_mul){
                                        $product->update(array('start_price'=>$price));
                                    }
                                }
                                $string .= $product->item_id . $sku_string.  $api_result['info'];
                            }else{
                                $string .= $product->item_id . '更新失败:' . $api_result['info'];
                            }
                            $sku_string = '';
                            $sku = [];
                            $i = 1;
                        } else {
                            $i++;
                        }
                    }
                    if (!empty($sku)) {
                        $api_result = $channel->changePrice($item_id, $sku, $is_mul,$product->site);
                        if ($api_result['status']) {
                            foreach($sku as $k => $price ){
                                $this->modelDetail->where('item_id',$product->item_id)->where('sku',$k)->update(array('start_price'=>$price));
                            }
                            $string .= $product->item_id . $sku_string.  $api_result['info'];
                        }else{
                            $string .= $product->item_id . '更新失败:' . $api_result['info'];
                        }
                    }
                }
                break;
            case 'updateShipFee';
                $ship_detail = request()->input("ship_detail");
                foreach ($ship_detail['id']['shipping'] as $key => $value) {
                    $product = $this->model->where('item_id', $key)->first();
                    $item_ship = json_decode($product->shipping_details, true);
                    $item_ship['Shipping']['1']['ShippingServiceCost'] = $value;
                    $item_ship['InternationalShipping']['1']['ShippingServiceCost'] = $ship_detail['id']['international'][$key];
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $api_result = $channel->changeShippingFee($product->item_id,$item_ship,$product->site);
                    if ($api_result['status']) {
                        $product->update(array('shipping_details'=>json_encode($item_ship)));
                        $string .= $product->item_id . $api_result['info'];
                    }else{
                        $string .= $product->item_id . '更新失败:' . $api_result['info'];
                    }

                }
                break;
            case 'endItems';
                $list = $this->model;
                $result = $list->whereHas('details', function ($query) use ($arr) {
                    $query = $query->whereIn('id', $arr);
                })->get();
                foreach ($result as $product) {
                    $account = AccountModel::find($product->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $api_result = $channel->endItems($product->item_id,'NotAvailable Sorry',$product->site);
                    if ($api_result['status']) {
                        $product->update(array('status' => 3));
                        foreach ($product->details as $detail) {
                            $detail->update(array('status' => 0));
                        }
                        $this->model->where('item_id',$product->item_id)->update(array('status',3));
                        $string .= $product->item_id . $api_result['info'];
                    } else {
                        $string .= $product->item_id . '更新失败:' . $api_result['info'];
                    }
                }
                break;
            case 'modifyPayPalEmailAddress';
                $pay_pal_list = request()->input("pay_pal");
                foreach ($pay_pal_list['id'] as $key => $value) {
                    $detail = $this->modelDetail->where('id', $key)->first();
                    $account = AccountModel::find($detail->ebayProduct->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $api_result = $channel->changePayPal($detail->item_id, $value,$detail->ebayProduct->site);
                    if ($api_result['status']) {
                        $re = $detail->ebayProduct->update(array('paypal_email_address' => $value));
                        $string .= $detail->item_id . '更新成功 ';
                    } else {
                        $string .= $detail->item_id . '更新失败:' . $api_result['info'];
                    }
                }
                break;
            case 'modifyProcessingDays';
                $processing_days = request()->input("processing_days");
                foreach ($processing_days['id'] as $key => $value) {
                    $detail = $this->modelDetail->where('id', $key)->first();
                    $account = AccountModel::find($detail->ebayProduct->account_id);
                    $channel = Channel::driver($account->channel->driver, $account->api_config);
                    $api_result = $channel->changeProcessingDays($detail->item_id, $value,$detail->ebayProduct->site);
                    if ($api_result['status']) {
                        $re = $detail->ebayProduct->update(array('dispatch_time_max' => intval($value)));
                        $string .= $detail->item_id . '更新成功 ';
                    } else {
                        $string .= $detail->item_id . '更新失败:' . $api_result['info'];
                    }
                }
                break;
            default;
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $string));
    }


    public function ajaxGetLog(){
        $get = request()->input();
        $where = [];
        $list = EbayReplenishmentLogModel::where($where);
        $account =  $this->model->getChannelAccount(4);
        if(isset($get['update_time_start_log'])&&!empty($get['update_time_start_log'])){
            $list->where('update_time','>',$get['update_time_start_log']);
        }
        if(isset($get['update_time_end_log'])&&!empty($get['update_time_end_log'])){
            $list->where('update_time','<',$get['update_time_end_log']);
        }
        if(isset($get['item_id_log'])&&!empty($get['item_id_log'])){
            $list->where('item_id',$get['item_id_log']);
        }
        if(isset($get['sku_log'])&&!empty($get['sku_log'])){
            $list->where('sku', 'like', '%' . $get['sku_log'] . '%');
        }
        if(isset($get['token_id_log'])&&!empty($get['token_id_log'])){
            $list->where('token_id',$get['token_id_log']);
        }
        if(isset($get['is_api_success_log'])&&!empty($get['is_api_success_log'])){ //is_api_success_log
            $list->where('is_api_success',$get['is_api_success_log']);
        }
        $result = $list->get();
        if(count($result)>0){
            foreach($result as $key => $v){
                $result[$key]['token_id'] = $account[$v['token_id']];
            }
            echo json_encode($result);
            die;
        }else{
            echo  json_encode(false);
            die;
        }




    }


}