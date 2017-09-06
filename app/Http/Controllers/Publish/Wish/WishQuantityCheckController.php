<?php
/*WISHI平台数量监控
 * add by ldb 2016-8-22
*/
namespace App\Http\Controllers\Publish\Wish;
use Channel;
use App\Models\Channel\AccountModel;
use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Publish\Wish\WishPublishProductModel;
use App\Models\Publish\Wish\WishPublishProductDetailModel;
use App\Models\Publish\Wish\WishSellerCodeModel;

class WishQuantityCheckController extends Controller
{
    public function __construct(WishPublishProductModel $wishProduct, WishPublishProductDetailModel $wishProductDetail,WishSellerCodeModel $sellerCode)
    {   //myecho($wishProduct);
        $this->model = $wishProductDetail;
        $this->mainIndex = route('WishQuantityCheck.index');
        $this->mainTitle = 'WISH在线数量监控';
        $this->viewPath = 'publish.wish.WishQuantityCheck.';
        $this->wishProduct = $wishProduct;
        $this->wishProductDetail = $wishProductDetail;
        $this->sellerCode = $sellerCode;
        $this->channel_id = ChannelModel::where('driver', 'wish')->first()->id;
       // echo  "<pre/>";
    }
    public function index(){
        request()->flash();
        $preurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->getNewMixedSearchAttribute(),
            'preurl'=>$preurl
        ];
        $response['mixedSearchFields']
        ['filterSelects'] = [
            'account_id' => $this->wishProduct->getChannelAccount(3),
            'sellerID' =>$this->sellerCode->getWishCodeWithName(),

        ];
    //myecho(@$response['data'][0]->belongs_product->number_sold);

        return view($this->viewPath . 'index', $response);

    }
    public function WishQuantityCheckget(){
        $this->index();
    }
    //修改在线SKU
    public function ajaxModifySku(){
        //$this->ajax_return('ok', 1,$_POST['type']);exit;
        $productId = $_POST['productId'];//SKU的WISH的ID
        $account_id = $_POST['account_id'];
        $skuId = $_POST['skuId'];//SKU的ID
        $sku = $_POST['sku'];
        $type = $_POST['type'];//修改类型
        $account = AccountModel::findOrFail($account_id);
        $wishApi = Channel::driver($account->channel->driver, $account->api_config);
        $variant = [];
        $variant['sku'] = $sku;
        if($type == 'stock'){
            //修改数量
            $SkuStock = $_POST['SkuStock'];//SKU数量
            $variant['inventory'] = $SkuStock;
            $url = 'https://china-merchant.wish.com/api/v2/variant/update';
        }elseif($type == 'Price'){
            //修改价格
            $price = $_POST['Price'];
            $variant['price'] = $price;
            $url = 'https://china-merchant.wish.com/api/v2/variant/update';
        }elseif($type == 'Freigh'){
            //修改运费
            $shipping = $_POST['Freigh'];
            $variant['shipping'] = $shipping;
            $url = 'https://china-merchant.wish.com/api/v2/variant/update';
        }elseif($type == 'disable'){
            //下架
            $url = 'https://china-merchant.wish.com/api/v2/variant/disable';
        }elseif($type == 'enable'){
            //上架
            $url = 'https://china-merchant.wish.com/api/v2/variant/enable';
        }else{
            $this->ajax_return('暂时不可以修改此类型!', 0, 3);
        }
        $result = $wishApi->updateProductVariation($variant, $url);
        if($result['status']){
            //成功
            switch($type){
                case 'enable':
                    $updata['enabled'] = 1;
                    $this->wishProductDetail->where('id', $skuId)->update($updata);
                    break;
                case 'disable':
                    $updata['enabled'] = 0;
                    $this->wishProductDetail->where('id', $skuId)->update($updata);
                    break;
                case 'stock':
                   $this->wishProductDetail->where('id', $skuId)->update($variant);
                    break;
                case 'Price':
                    $this->wishProductDetail->where('id', $skuId)->update($variant);
                    break;
                case 'Freigh':
                    $this->wishProductDetail->where('id', $skuId)->update($variant);
                    break;
                default:
                    $this->ajax_return($result['info'],0,'type');
                    break;
            }
            $this->ajax_return($result['info'],1,$type);
        }else{
            $this->ajax_return($result['info'],0,'type');
        }
    }
    //批量修改价格、数量、运费、上下架
    public function BatchOperation(){

        $data = $_POST;
        $type = $data['BoAction'];
        if(!$type){
            exit('error');
        }

        $idArray = explode(',',$data['IdStr']);
        $ModifyDate = $data['ModifyDate'];
        $preurl = $data['preurl'];
        $success = array();
        $fail = array();
        foreach($idArray as $v){
            $ProDetail = WishPublishProductDetailModel::findOrFail($v);
            $account = AccountModel::findOrFail($ProDetail->account_id);
            $wishApi = Channel::driver($account->channel->driver, $account->api_config);
            $skuId =$v;
            $variant = array();
            $variant['sku'] = $ProDetail->sku;
            if($type == 'stock'){
                //修改数量
                $SkuStock = $ModifyDate;//SKU数量
                $variant['inventory'] = $SkuStock;
                $url = 'https://china-merchant.wish.com/api/v2/variant/update';
            }elseif($type == 'Price'){
                //修改价格
                $variant['price'] = $ModifyDate;
                $url = 'https://china-merchant.wish.com/api/v2/variant/update';
            }elseif($type == 'Freigh'){
                //修改运费
                $variant['shipping'] = $ModifyDate;
                $url = 'https://china-merchant.wish.com/api/v2/variant/update';
            }elseif($type == 'disable'){
                //下架
                $url = 'https://china-merchant.wish.com/api/v2/variant/disable';
            }elseif($type == 'enable'){
                //上架
                $url = 'https://china-merchant.wish.com/api/v2/variant/enable';
            }else{
                $this->ajax_return('暂时不可以修改此类型!', 0, 3);
            }

            $result = $wishApi->updateProductVariation($variant, $url);
            if($result['status']){
                switch($type){
                    case 'enable':
                        $variant['enabled'] = 1;
                        $res = $this->wishProductDetail->where('id', $skuId)->update($variant);
                        if($res){
                            $success[] = $skuId;
                        }else{
                            $fail[] = $skuId;
                        }
                        break;
                    case 'disable':
                        $variant['enabled'] = 0;
                        $res = $this->wishProductDetail->where('id', $skuId)->update($variant);
                        if($res){
                            $success[] = $skuId;
                        }else{
                            $fail[] = $skuId;
                        }
                        break;
                    case 'stock':
                        $variant['inventory'] = $ModifyDate;
                        $res = $this->wishProductDetail->where('id', $skuId)->update($variant);
                        if($res){
                            $success[] = $skuId;
                        }else{
                            $fail[] = $skuId;
                        }
                        break;
                    case 'Price':
                        $variant['price'] = $ModifyDate;
                        $res = $this->wishProductDetail->where('id', $skuId)->update($variant);
                        if($res){
                            $success[] = $skuId;
                        }else{
                            $fail[] = $skuId;
                        }
                        break;
                    case 'Freigh':
                        $variant['shipping'] = $ModifyDate;
                        $res = $this->wishProductDetail->where('id', $skuId)->update($variant);
                        if($res){
                            $success[] = $skuId;
                        }else{
                            $fail[] = $skuId;
                        }
                        break;
                    default:
                        return redirect($preurl)->with('alert', $this->alert('danger',  '修改失败'));
                        exit('error');
                        break;
                }
            }else{
                $fail[] = $skuId;
            }

        }
        //myecho($res);
        if($success && $fail){
            $fail = implode($fail,',');
            return redirect($preurl)->with('alert', $this->alert('danger',  '部分成功,失败的ID:'.$fail));
        }elseif($success && !$fail){
            return redirect($preurl)->with('alert', $this->alert('success',  '修改全部成功'));
        }elseif(!$success && $fail){
            $fail = implode($fail,',');
            //return redirect($preurl)->with('alert', $this->alert('danger',  '修改全部失败'));
            return redirect($preurl)->with('alert', $this->alert('danger',  '修改全部失败,失败的ID:'.$fail));
        }
        return redirect($preurl)->with('alert', $this->alert('danger',  'error'));
    }

    //搜索条件
    public function getNewMixedSearchAttribute()
    {
        return [
            'filterSelects' => [
            ],
            'filterFields' => [
                'productID',
            ],
            'relatedSearchFields' => [
                'details'=>['wish_sku']
            ]
        ];
    }
    function ajax_return($info = '', $status = 1, $type = '')
    {
        $result = array('type' => $type, 'info' => $info, 'status' => $status);
        exit(json_encode($result));
    }
}