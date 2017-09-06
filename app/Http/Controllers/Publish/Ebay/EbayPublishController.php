<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-05
 * Time: 14:32
 */
namespace App\Http\Controllers\Publish\Ebay;

use Tool;
use Channel;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\Publish\Ebay\EbaySellerCodeModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\Publish\Ebay\EbayCategoryModel;
use App\Models\Publish\Ebay\EbaySpecificsModel;
use App\Models\Publish\Ebay\EbayConditionModel;
use App\Models\Publish\Ebay\EbayShippingModel;
use App\Models\Publish\Ebay\EbayDataTemplateModel;
use App\Models\Publish\Ebay\EbayDescriptionTemplateModel;
use App\Models\Publish\Ebay\EbayAccountSetModel;
use App\Models\Publish\Ebay\EbayStoreCategorySetModel;
use App\Models\Publish\Ebay\EbayTimingSetModel;

use App\Models\PaypalsModel;
use App\Jobs\AutoPublish;
use Illuminate\Foundation\Bus\DispatchesJobs;


use App\Models\ItemModel;


class EbayPublishController extends Controller
{
    use  DispatchesJobs;
    public function __construct(EbayPublishProductModel $ebayProduct,
                                EbayPublishProductDetailModel $ebayProductDetail,
                                EbaySellerCodeModel $sellerCode,
                                EbaySiteModel $ebaySite,
                                EbayCategoryModel $ebayCategory,
                                EbaySpecificsModel $ebaySpecifics,
                                EbayConditionModel $ebayCondition,
                                EbayShippingModel $ebayShipping,
                                EbayDataTemplateModel $dataTemplate,
                                EbayDescriptionTemplateModel $descriptionTemplate,
                                EbayAccountSetModel $accountSetModel,
                                EbayStoreCategorySetModel $storeSet,
                                ItemModel $itemModel,
                                PaypalsModel $payPal)
    {
        $this->model = $ebayProduct;
        $this->mainIndex = route('ebayPublish.index');
        $this->mainTitle = 'Ebay草稿刊登';
        $this->viewPath = 'publish.ebay.publish.';
        $this->modelDetail = $ebayProductDetail;
        $this->sellerCode = $sellerCode;
        $this->ebaySite = $ebaySite;
        $this->ebaySpecifics = $ebaySpecifics;
        $this->ebayCondition = $ebayCondition;
        $this->ebayShipping = $ebayShipping;
        $this->dataTemplate = $dataTemplate;
        $this->ebayCategory = $ebayCategory;
        $this->descriptionTemplate = $descriptionTemplate;
        $this->accountSet = $accountSetModel;
        $this->storeSet = $storeSet;
        $this->item = $itemModel;
        $this->payPal = $payPal;
    }






    public function index()
    {
        request()->flash();
        $list = $this->model->whereIn('status', ['0','1']);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$list),
        ];

        $response['mixedSearchFields']=[
            'filterSelects' => [
                'site_name' => $this->ebaySite->getSite(),
                 'account_id' => $this->model->getChannelAccount(),
                'currency' => $this->ebaySite->getSite('currency', 'currency'),
                'paypal_email_address' => $this->payPal->getPayPal('paypal_email_address'),
                'listing_type' => [
                    'Chinese' => 'Chinese',
                    'FixedPriceItem' => 'FixedPriceItem'
                ],
                'multi_attribute' => [
                    1 => '是',
                    0 => '否',
                ],
                'status' => [
                    '0' => '草稿',
                    '1' => '待发布'
                ],
            ],

            'sectionSelect' => [
                'time' => ['created_at']
            ],
            'selectRelatedSearchs' => [
                'details' =>[
                    'seller_id' =>$this->sellerCode->getEbayCodeWithName(),
                ],
            ],
            'relatedSearchFields' => [
                'details' =>[
                   'erp_sku',
                ],
            ]

//
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'site' =>$this->ebaySite->getSite('site_id'),
            'account' => $this->model->getChannelAccount(),
            'description' => $this->descriptionTemplate->get()->lists('name', 'id')
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'site' =>$this->ebaySite->getSite('site_id'),
            'account' => $this->model->getChannelAccount(),
            'description' => $this->descriptionTemplate->get()->lists('name', 'id'),
            'condition' => $this->ebayCondition->getSiteCategoryCondition($model->primary_category,$model->site),
            'shipping'=>  $this->ebayShipping->where(['site_id'=>$model->site])->get(),
            'siteInfo'=> $this->ebaySite->where(['site_id'=>$model->site])->first(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function returnDraft(){
        $id = request()->input('id');
        $model = $this->model->find($id);
        $model->update(['status'=>0]);
        echo true;
    }




    public function ajaxSuggestCategory(){
        $site = request()->input('site');
        $key_word = request()->input('key_word');
        $return = $this->ebayCategory->getSuggestCategory($key_word,$site);
        echo json_encode($return);
        die;

    }

    public function ajaxInitSite(){
        $where =[];
        $where['site_id'] = request()->input('site');
        $return = [];
        $shipping = $this->ebayShipping->where($where)->get();
        $ship_text ='<option value="">==请选择==</option>';
        $international_text ='<option value="">==请选择==</option>';
        foreach($shipping as $ship){
            if($ship->valid_for_selling_flow==1){
                $days = '';
                if(!($ship->shipping_time_min ==0&&$ship->shipping_time_max==0)){
                    $days ='('. $ship->shipping_time_min.'-'.$ship->shipping_time_max.')';
                }
                if($ship->international_service==2){
                    $ship_text .='<option value="'.$ship->shipping_service.'">'.$ship->description.$days.'</option>';
                }
                if($ship->international_service==1){
                    $international_text .='<option value="'.$ship->shipping_service.'">'.$ship->description.$days.'</option>';
                }
            }
        }
        $return['ship_text'] = $ship_text;
        $return['international_text'] = $international_text;

        $siteInfo = $this->ebaySite->where($where)->first();
        $returns_with_in = '<option value="">==请选择==</option>';
        $shipping_costpaid_by = '<option value="">==请选择==</option>';
        $refund ='<option value="">==请选择==</option>';
        if(!empty($siteInfo)){
            $returns_with_in_data = json_decode($siteInfo->returns_with_in,true);
            if(!empty($returns_with_in_data)){
                foreach($returns_with_in_data as $v){
                    $returns_with_in .='<option value="'.$v.'">'.$v.'</option>';
                }
            }
            $shipping_costpaid_by_data = json_decode($siteInfo->shipping_costpaid_by,true);
            if(!empty($shipping_costpaid_by_data)){
                foreach($shipping_costpaid_by_data as $v){
                    $shipping_costpaid_by .='<option value="'.$v.'">'.$v.'</option>';
                }
            }

            $refund_data = json_decode($siteInfo->refund,true);
            if(!empty($refund_data)){
                foreach($refund_data as $v){
                    $refund .='<option value="'.$v.'">'.$v.'</option>';
                }
            }
        }
        $return['returns_with_in'] = $returns_with_in;
        $return['shipping_costpaid_by'] = $shipping_costpaid_by;
        $return['refund'] = $refund;
        echo json_encode($return);
        die;

    }

    public function ajaxInitCategory(){
        $where =[];
        $where['site'] = request()->input('site');
        $where['category_level'] =  request()->input('level') +1;
        $category_parent_id = request()->input('category_parent_id');

        if(!empty($category_parent_id)){
            $where['category_parent_id']  = $category_parent_id;
        }
        $category =  $this->ebayCategory->where($where)->get(['category_id','category_level','leaf_category','category_name'])->toArray();
        echo json_encode($category);
    }



    public function ajaxInitErpData(){
        $return = [];
        $sku = request()->input('sku');
        if(empty($sku)){
            json_encode(false);
            die;
        }
        $type =  request()->input('type');
        $type = explode('+',$type);

        $skuNew = explode('*',$sku);
        $sku = count($skuNew)>1?$skuNew[1]:$skuNew[0];
        $skuFore = count($skuNew)>1?$skuNew[0]:'';
        $skuNew = explode('(',$sku);
        $sku = $skuNew[0];
        $skuRear = count($skuNew)>1?$skuNew[1]:'';
        $description =  '';
        //$skuNew = $this->handleSku($sku);
          /*  foreach($item->product->shape as $image)
                                <a href="{{ asset($image) }}" target='_blank' ><img src="{{ asset($image) }}" width="244px" ></a>*/


        foreach($type as $v){
            if ($v == 'sku') {
                $erpSku = $this->item-> where('sku', 'like', $sku.'%')->get();
                foreach ($erpSku as $key=> $te) {
                    if($key==0){
                        $description = htmlspecialchars_decode($te->html_mod);
                    }
                    $erpSku = $te->sku;
                    if (!empty($skuFore)) {
                        $erpSku = $skuFore . '*' . $erpSku;
                    }
                    if (!empty($skuRear)) {
                        $erpSku = $erpSku.'('.$skuRear;
                    }
                    $return['sku'][] = $erpSku;
                }
            }
            if($v=='picture'){
                $return['picture'] = [];
                /*
                $return['picture'][] = 'http://www.v3.slme.com//default.jpg';
                $return['picture'][] = 'http://www.v3.slme.com//default.jpg';
                $return['picture'][] = 'http://www.v3.slme.com//default.jpg';
                $return['picture'][] = 'http://www.v3.slme.com//default.jpg';
                $return['picture'][] = 'http://www.v3.slme.com//default.jpg';
                $erpSku = $this->item-> where('sku', 'like', $sku.'%')->first();
                foreach($erpSku->product->shape as $image){
                    $return['picture'][]=asset($image);
                }*/


            }
        }

        $return['description'] = $description;
        echo json_encode($return);
        die;

    }

    public function ajaxInitSpecifics(){
        $site = request()->input('site');
        $category_id =  request()->input('category_id');
        $return = [];
        $result = $this->ebaySpecifics->getSiteCategorySpecifics($category_id,$site);
        $i = 0;
        if($result){
            foreach ($result as $re) {
                $return[$i]['name'] = $re['name'];
                if($re['min_values']>0){
                    $return[$i]['must'] = true;
                }else{
                    $return[$i]['must'] = false;
                }
                $text = '<option value="">==请选择==</option>';
                $specific_values = json_decode($re['specific_values'], true);
                if (!empty($specific_values)) {
                    foreach ($specific_values as $s_v) {
                        $text .= '<option value="' . $s_v . '">' . $s_v . '</option>';
                    }
                }
                $return[$i]['text'] = $text;
                $i++;

            }
            echo json_encode($return);
            die;
        }else{
            echo false;
            die;
        }

    }

    public function ajaxInitCondition(){
        $site = request()->input('site');
        $category_id =  request()->input('category_id');
        $return = [];
        $result = $this->ebayCondition->getSiteCategoryCondition($category_id,$site);
        if($result){
            $text = '<option value="">==请选择==</option>';
            foreach($result as $key=> $re){
                if($key==0){
                    $return['is_variations'] = $re['is_variations'];
                    $return['is_upc'] = $re['is_upc'];
                    $return['is_ean'] = $re['is_ean'];
                    $return['is_isbn'] = $re['is_isbn'];
                }
                $text .=  '<option value="' . $re['condition_id'] . '">' . $re['condition_name'] . '</option>';
            }
            $return['text'] = $text;
            echo json_encode($return);
            die;

        }else{
            echo false;
            die;
        }
    }

    /**
     * 数据模板
     */
    public function ajaxSetDataTemplate(){
        $site = request()->input('site');
        $warehouse = request()->input('warehouse');
        $ebay_sku = request()->input('ebay_sku');
        $sku_price = request()->input('price');
        $erp_sku = Tool::filter_sku($ebay_sku);
        $erp_sku = $erp_sku[0]['erpSku'];
      //  $sku_weight = 2.3;//获取sku的重量
        $sku_weight = $this->item->where('sku','like',$erp_sku.'%')->first()->weight;

        $data = [];
        $is_success = false;
        $string = '未找到满足条件数据模板！';
        $template =  $this->dataTemplate->where(['site'=>$site,'warehouse'=>$warehouse])->get();
        if(!count($template)==0){
            foreach($template as $tem){
                if(($sku_weight>$tem->start_weight)&&($sku_weight<$tem->end_weight)&&($sku_price>$tem->start_price)&&($sku_price<$tem->end_price)){
                    $data =  $tem->toArray();
                    $data['buyer_requirement'] = json_decode($data['buyer_requirement'],true);
                    $data['return_policy'] = json_decode($data['return_policy'],true);
                    $data['shipping_details'] = json_decode($data['shipping_details'],true);
                    $is_success =true;
                    break;
                }
            }

        }
        $this->ajax_return($string,$is_success,$data);

    }
    /**
     * @param $sku
     * $return = [
     *      'sku'
     *      'prefix'
     *      'num'
     *
     * ]
     */
    public function handleSku($sku){
        $return = [];
        $skuMid = explode('*',$sku);
        $return['sku'] = count($skuMid)>1?$skuMid[1]:$skuMid[0];
        $return['prefix'] = count($skuMid)>1?$skuMid[0]:"";
        $return['num'] = 1;
    }








    public function store(){
        $action = request()->input('action');
        $post = $_POST;
        if($action=='save'){
            $this->save($post);
        }
        if($action =='verify'){
            $result =  $this->save($post,true);
            $this->doAction($result,'Verify');
        }
        if($action =='editAndPost'){
            $result =  $this->save($post,true);
            $this->doAction($result,'Add');
        }
        if($action=='prePost'){
            $result =  $this->save($post,true);
            $this->addQueue($result);
        }
        exit;
    }



    public function save($post, $exit = false){
        $sellerIdInfo = $this->sellerCode->getAllEbayCode();
        $accountInfo = $this->model->getChannelAccount();
        $result = [];


        if(isset($post['specify_image'])){ //说明存在 指定橱窗图
            $specify_image =$post['specify_image']; //指定变量
        }


        foreach ($post['choose_account'] as $k => $account) {

            $is_add =true;
            $ebay_product = array();
            $ebay_product_detail = array();
            $ebay_product['account_id'] = $account;
            $ebay_product['item_id'] = '';
            $ebay_product['warehouse'] = $post['warehouse'];
            $ebay_product['primary_category'] = $post['primary_category'];
            $ebay_product['secondary_category'] = $post['secondary_category'];
            $ebay_product['title'] = isset($post['title'][$account])?$post['title'][$account]:'';
            $ebay_product['sub_title'] = isset($post['sub_title'][$account])?$post['sub_title'][$account]:'';
            $ebay_product['sku'] = trim($post['ebay_sku']);

            $sell_code = Tool::getSellCode($ebay_product['sku']);
            $ebay_product['seller_id'] = isset($sellerIdInfo[$sell_code])?$sellerIdInfo[$sell_code]:'';


            $ebay_product['site'] = $post['site'];
            $ebay_product['site_name'] = $this->ebaySite->where('site_id',$ebay_product['site'])->first()->site;
            $ebay_product['currency'] = $this->ebaySite->where('site_id',$ebay_product['site'])->first()->currency;

            $ebay_product['listing_type'] = $post['listing_type']==1?'Chinese':'FixedPriceItem';

            $ebay_product['view_item_url'] = '';
            $ebay_product['listing_duration'] = $post['listing_duration'];

            $ebay_product['payment_methods'] = 'PayPal';


            $ebay_product['condition_id'] = isset($post['condition_id'])?$post['condition_id']:'';
            $ebay_product['condition_description'] = isset($post['condition_description'])?$post['condition_description']:'';

            $ebay_product['location'] = $post['location'];
            $ebay_product['postal_code'] = $post['postal_code'];
            $ebay_product['country'] = $post['country'];

            $ebay_product['dispatch_time_max'] = $post['dispatch_time_max'];
            $ebay_product['quantity_sold'] = 0;
            $ebay_product['is_out_control'] = 1;





            $item_specifics = isset($post['item_specifics'])?$post['item_specifics']:array();
            $ebay_product['item_specifics'] = json_encode($item_specifics);

            $ebay_product['description'] = htmlspecialchars($post['description']);


            //替换图片链接
            $picture_details = isset($post['picture_details'])?$post['picture_details']:array();

            if(isset($specify_image)){
                if(!isset($specify_image[0])){
                    unset($specify_image);
                    $specify_image =$post['specify_image'];


                }
                $un_key =  array_search($specify_image[0],$picture_details);
                unset($picture_details[$un_key]); //删除这个元素
                array_unshift($picture_details,$specify_image[0]); //首部加个元素
                unset($specify_image[0]);//把这个变量的值也去除
                if(is_array($specify_image)){
                    $specify_image = array_values($specify_image);
                }
            }

            $ebay_product['picture_details'] = json_encode($picture_details);
            $ebay_product['description_picture'] = isset($post['description_picture'])?json_encode($post['description_picture']):json_encode(array());

            $ebay_product['private_listing'] = false;




            $return_policy = [];
            $return_policy['ReturnsAcceptedOption'] = isset($post['returns_option'])?$post['returns_option']:'';
            $return_policy['ReturnsWithinOption'] = $post['returns_with_in'];
            $return_policy['RefundOption'] = isset($post['refund'])?$post['refund']:'';
            $return_policy['ShippingCostPaidByOption'] = $post['shipping_costpaid_by'];
            $return_policy['Description'] = $post['refund_description'];
            $return_policy['ExtendedHolidayReturns'] = isset($post['extended_holiday'])?$post['extended_holiday']:'';
            $ebay_product['return_policy'] = json_encode($return_policy);

            $buyer_requirement = [];
            $buyer_requirement['LinkedPayPalAccount'] = isset($post['no_paypal'])?$post['no_paypal']:'';
            $buyer_requirement['ShipToRegistrationCountry'] = isset($post['no_ship'])?$post['no_ship']:'';

            $buyer_requirement['unpaid_on'] = isset($post['unpaid_on'])?$post['unpaid_on']:'';
            $buyer_requirement['MaximumUnpaidItemStrikesInfo']['Count'] = isset($post['unpaid'])?$post['unpaid']:'';
            $buyer_requirement['MaximumUnpaidItemStrikesInfo']['Period'] = isset($post['unpaid_day'])?$post['unpaid_day']:'';

            $buyer_requirement['policy_on'] = isset($post['policy_on'])?$post['policy_on']:'';
            $buyer_requirement['MaximumBuyerPolicyViolations']['Count'] = isset($post['policy'])?$post['policy']:'';
            $buyer_requirement['MaximumBuyerPolicyViolations']['Period'] = isset($post['policy_day'])?$post['policy_day']:'';

            $buyer_requirement['feedback_on'] = isset($post['feedback_on'])?$post['feedback_on']:'';
            $buyer_requirement['MinimumFeedbackScore'] = isset($post['feedback'])?$post['feedback']:'';
            $buyer_requirement['item_count_on'] = isset($post['item_count_on'])?$post['item_count_on']:'';
            $buyer_requirement['MaximumItemRequirements']['MaximumItemCount'] = isset($post['item_count'])?$post['item_count']:'';
            $buyer_requirement['MaximumItemRequirements']['MinimumFeedbackScore'] = isset($post['item_count_feedback'])?$post['item_count_feedback']:'';

            $ebay_product['buyer_requirement'] = json_encode($buyer_requirement);


            $shipping_details = [];

            foreach($post['shipping'] as $key => $v){
                if(!empty($v['ShippingService'])){
                    $shipping_details['Shipping'][$key]['ShippingService'] = $v['ShippingService'];
                    $shipping_details['Shipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost'])?round($v['ShippingServiceCost'],2):'0.00';
                    $shipping_details['Shipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost'])?round($v['ShippingServiceAdditionalCost'],2):'0.00';
                }
            }
            foreach($post['InternationalShipping'] as $key => $v){
                if(!empty($v['ShippingService'])){
                    $shipping_details['InternationalShipping'][$key]['ShippingService'] = $v['ShippingService'];
                    $shipping_details['InternationalShipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost'])?round($v['ShippingServiceCost'],2):'0.00';
                    $shipping_details['InternationalShipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost'])?round($v['ShippingServiceAdditionalCost'],2):'0.00';
                    $shipping_details['InternationalShipping'][$key]['ShipToLocation'] = isset($v['ShipToLocation'])?$v['ShipToLocation']:array();
                }
            }

            $shipping_details['ExcludeShipToLocation'] =isset($post['un_ship'])?$post['un_ship']:array();
            $ebay_product['shipping_details'] = json_encode($shipping_details);


            if($post['listing_type'] == 1){
                $ebay_product['multi_attribute'] = 0;
                $ebay_product['private_listing'] = isset($post['private_listing'])?$post['private_listing']:false;
                $ebay_product['start_price'] =$post['start_price1'];
                $ebay_product['quantity'] =$post['quantity1'];
            }

            if($post['listing_type'] == 2){
                $ebay_product['multi_attribute'] = 0;

                $ebay_product['start_price'] =isset($post['mulAccount'][$ebay_product['sku']][$account])?$post['mulAccount'][$ebay_product['sku']][$account]:$post['start_price2'];

                $ebay_product['quantity'] =$post['quantity2'];


            }

            if($post['listing_type'] == 3){
                $ebay_product['multi_attribute'] = 1;
                $variation = isset($post['variation'])?$post['variation']:array();
                $variation_new = [];
                if(!empty($variation)){
                    foreach($variation as $key=> $var){
                        if(!empty($var)){
                            foreach($post['variation'.$key] as $v){
                                if(!empty($v)){
                                    $variation_new[$var][]=$v;
                                }
                            }
                        }
                    }
                }
                $ebay_product['variation_specifics'] = json_encode($variation_new);
                $variation_picture = isset($post['variation_picture'])?$post['variation_picture']:array();
                $variation_picture_new = [];
                if(!empty($variation_picture)){
                    foreach($variation_picture as $key=> $v){
                        if(!empty($v)){
                            $variation_picture_new[$post['variation'][0]][$key] = $v;
                        }
                    }
                }
                $ebay_product['variation_picture'] = json_encode($variation_picture_new);

                foreach($post['sku'] as $key=> $sku){
                    if(isset($post['mulAccount'][$sku][$ebay_product['account_id']])){
                        $new_price = empty($post['mulAccount'][$sku][$ebay_product['account_id']])?0.00:$post['mulAccount'][$sku][$ebay_product['account_id']];
                    }else{
                        $new_price = 0.00;
                    }
                    $ebay_product_detail[$key]['start_price'] =empty($new_price)?$post['start_price'][$key]:$new_price;
                    $ebay_product_detail[$key]['sku'] = $sku;
                    $ebay_product_detail[$key]['product_id'] = 0;
                    $ebay_product_detail[$key]['quantity'] = $post['quantity'][$key];
                    $ebay_product_detail[$key]['erp_sku'] = '';
                    $ebay_product_detail[$key]['quantity_sold'] = 0;
                    $ebay_product_detail[$key]['item_id'] = '';
                    $ebay_product_detail[$key]['seller_id'] = 0;
                    $ebay_product_detail[$key]['status'] = 0;
                }
                $ebay_product['start_price'] = $ebay_product_detail[0]['start_price'];
                $ebay_product['quantity'] = $ebay_product_detail[0]['quantity'];

            }
            if(empty($ebay_product_detail)){
                $ebay_product_detail[0]['sku'] = $post['ebay_sku'];
                $ebay_product_detail[0]['product_id'] = 0;
                $ebay_product_detail[0]['start_price'] =$ebay_product['start_price'];
                $ebay_product_detail[0]['quantity'] = $ebay_product['quantity'];
                $ebay_product_detail[0]['erp_sku'] = '';
                $ebay_product_detail[0]['quantity_sold'] = 0;
                $ebay_product_detail[0]['item_id'] = '';
                $ebay_product_detail[0]['seller_id'] = 0;
                $ebay_product_detail[0]['status'] = 0;
            }

            $is_error = false;
            $is_error_info = '';
            $catalog_id = '';
            $domain ='';
            $image_domain = '';
            $account_info =  AccountModel::where('id',$ebay_product['account_id'])->first();
            if(empty($account_info->domain)){
                $is_error = true;
                $is_error_info = '未找到对应账号后缀';
            }else{
                $domain = $account_info->domain;
            }
            if(empty($account_info->image_domain)){
                $is_error = true;
                $is_error_info = '未找到对应账号图片域名';
            }else{
                $image_domain = $account_info->image_domain;
            }


            if(!empty($image_domain)){ //替换图片地址

            }

            if(!empty($domain)){ //重置sku账号后缀
                $ebay_product['sku']= Tool::getNewEbaySku($post['ebay_sku'],$domain);
            }

            foreach($ebay_product_detail as $key=> $sku){


                $ebay_product_detail[$key]['erp_sku'] = Tool::getErpSkuBySku($sku['sku']);
                if(!empty($domain)){ //重置sku账号后缀
                    $ebay_product_detail[$key]['sku']= Tool::getNewEbaySku($sku['sku'],$domain);
                }
                $site = $ebay_product['site'];
                $account_id = $ebay_product['account_id'];
                $list = $this->modelDetail->where(['erp_sku'=>$ebay_product_detail[$key]['erp_sku'],'status'=>'1']);
                $item_id = $list->whereHas('ebayProduct', function ($query) use($site,$account_id) {
                    $query = $query->where(['site'=>$site,'account_id'=>$account_id]);
                })->first();
                $sell_code = Tool::getSellCode($sku['sku']);
                $ebay_product_detail[$key]['seller_id'] = isset($sellerIdInfo[$sell_code])?$sellerIdInfo[$sell_code]:'';
                $item_info = $this->item->where('sku',$ebay_product_detail[$key]['erp_sku'])->first();

                $ebay_product_detail[$key]['product_id'] = empty($item_info->id)?0:$item_info->id;
                if(isset($item_info->catalog_id)){
                    $catalog_id =  $item_info->catalog_id;
                }
                //检测sku 是否有重复了刊登了
                if(!empty($item_id->item_id)){
                    $is_error = true;
                    $is_error_info = '重复广告'.$item_id->item_id;
                    break;
                }
            }
            if(!empty($catalog_id)){
                $category_description = $this->storeSet->getCategoryByAccount($ebay_product['account_id'],$catalog_id,$ebay_product['site'],$ebay_product['warehouse']);
                if(!empty($category_description)){
                    if(!empty($category_description['store_category'])){
                        $ebay_product['store_category_id'] = $category_description['store_category'];

                    }else{
                        $is_error = true;
                        $is_error_info = '未找满足条件的Ebay店铺分类';
                    }


                    if(!empty($category_description['description_id'])){
                        $ebay_product['description_id'] = $category_description['description_id'];
                    }else{
                        $is_error = true;
                        $is_error_info = '未找满足条件的描述模板';
                    }

                }else{
                    $is_error = true;
                    $is_error_info = '未找满足条件的Ebay店铺分类';
                }
            }else{
                $is_error = true;
                $is_error_info = '未找到sku在Erp的分类';
            }

            $paypal_email_address = $this->accountSet->getPayPalByPrice($ebay_product_detail[0]['start_price'],$ebay_product['account_id'],$ebay_product['currency']);
            if($paypal_email_address){
                $ebay_product['paypal_email_address'] = $paypal_email_address;
            }else{
                $is_error = true;
                $is_error_info = '未匹配到对应PayPal';
            }


            //检测匹配的ebay店铺分类 和 描述模板

           // $ebay_product['description_id'] = isset($post['description_id'])?$post['description_id']:0;

         //  var_dump($ebay_product_detail);

            if($is_error){
                $result[$k]['id'] = '';
                $result[$k]['is_success'] = false;
                $result[$k]['account'] = $accountInfo[intval($account)];
                $result[$k]['info'] =$is_error_info ;
                continue;
            }

            if (!empty($post['id'])) {
                $ids = explode(',', $post['id']);
                foreach ($ids as $v) {
                    if (!empty($v)) {
                        $thisProduct = $this->model->where('id', $v)->where('account_id', $account)->where('site',$ebay_product['site'])->first();
                        if ($thisProduct) {
                            $is_add = false;
                            $mark_id = $v;
                            break;
                        }
                    }
                }
            }

            if($is_add){
                $ebay_product['status'] = 0;
                $create  = $this->model->create($ebay_product);
                foreach($ebay_product_detail as $detail){
                    $detail['publish_id'] = $create->id;
                    $this->modelDetail->create($detail);
                }
                $result[$k]['id'] = $create->id;
                $result[$k]['is_success'] = true;
                $result[$k]['account'] = $accountInfo[intval($account)];
                $result[$k]['info'] = '新增成功';
            }else{
                $this->model->where('id',$mark_id)->update($ebay_product);
                $this->modelDetail->where('publish_id',$mark_id)->delete();
                foreach($ebay_product_detail as $detail){
                    $detail['publish_id'] = $mark_id;
                    $this->modelDetail->create($detail);
                }
                $result[$k]['id'] = $mark_id;
                $result[$k]['is_success'] = true;
                $result[$k]['account'] = $accountInfo[intval($account)];
                $result[$k]['info'] = '修改成功';
            }

        }

        $string = '';
        $id = [];

        foreach ($result as $re) {
            $string = $string . '<br/>' . $re['account'] . ' :' . $re['info'];
            $id[] = $re['id'];
        }

        if ($exit) {
            return $result;
        } else {
            $this->ajax_return($string, 1, implode(',', $id));
        }

    }
    public function doAction($data,$api){
        foreach($data as $key=>$value){
            if($value['is_success']){
                $result =  $this->model->publish($value['id'],$api);
                if($result['is_success']){
                    if($api=='Verify'){
                        $data[$key]['info'] = '刊登预计费用:'.$result['info'];
                    }
                    if($api=='Add'){ //刊登成功
                        $this->model->where('id',$value['id'])->update([
                            'status'=>'2',
                            'item_id'=>$result['info'],
                            'start_time'=>date('Y-m-d H:i:s')
                        ]);

                        $this->modelDetail->where('publish_id',$value['id'])->update([
                            'item_id'=>$result['info'],
                            'status'=>'1',
                        ]);
                        $data[$key]['info'] = '刊登成功:'.$result['info'];
                    }
                }else{
                    $data[$key]['info'] = $result['info'];
                }
            }
        }
        $string = '';
        $id = [];
        foreach ($data as $re) {
            $string = $string . '<br/>' . $re['account'] . ' :' . $re['info'];
            $id[] = $re['id'];
        }
        $this->ajax_return($string, 1, implode(',', $id));
    }

    public function addQueue($data){
        $timingSet = new EbayTimingSetModel();
        foreach($data as $key=>$value){
            if($value['is_success']){
                $info = $this->model->where('id',$value['id'])->first();
                if( $info->status ==1){
                    $data[$key]['info'] = '该广告已处于预刊登状态';
                }else{
                    $job = new AutoPublish($value['id']);

                    $where = [];
                    $where['site'] = $info->site;
                    $where['warehouse'] = $info->warehouse;
                    $where['account_id'] = $info->account_id;
                    $rand_time = $timingSet->getSiteTime($where);
                    if(!$rand_time){
                        $rand_time = rand(10, 100); //随机产生延迟毫秒数

                    }
                    $job = $job->onQueue('autoPublish')->delay($rand_time);
                    $this->dispatch($job);
                    $this->model->where('id',$value['id'])->update(['status'=>'1','start_time'=>date('Y-m-d H:i:s',time()+$rand_time)]);
                    $data[$key]['info'] = '设置预刊登完成';
                }
            }
        }
        $string = '';
        $id = [];
        foreach ($data as $re) {
            $string = $string . '<br/>' . $re['account'] . ' :' . $re['info'];
            $id[] = $re['id'];
        }
        $this->ajax_return($string, 1, implode(',', $id));
    }



    function ajax_return($info = '', $status = 1, $data = '')
    {
        $result = array('data' => $data, 'info' => $info, 'status' => $status);
        exit(json_encode($result));
    }

}