<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-29
 * Time: 13:52
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
use App\Models\PaypalsModel;
use App\Jobs\AutoPublish;
use Illuminate\Foundation\Bus\DispatchesJobs;

use App\Models\ItemModel;


class EbayOnlineController extends Controller
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
                                EbayDescriptionTemplateModel $descriptionTemplate,
                                PaypalsModel $payPal)
    {
        $this->model = $ebayProduct;
        $this->mainIndex = route('ebayOnline.index');
        $this->mainTitle = 'Ebay在线数据';
        $this->viewPath = 'publish.ebay.online.';
        $this->modelDetail = $ebayProductDetail;
        $this->sellerCode = $sellerCode;
        $this->ebaySite = $ebaySite;
        $this->ebayCategory = $ebayCategory;
        $this->ebaySpecifics = $ebaySpecifics;
        $this->ebayCondition = $ebayCondition;
        $this->ebayShipping = $ebayShipping;
        $this->descriptionTemplate = $descriptionTemplate;
        $this->payPal = $payPal;


    }


    public function index()
    {
        request()->flash();
        $list = $this->model->whereIn('status', ['2', '3']);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, $list),
        ];

        $response['mixedSearchFields'] = [
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
                    '2' => '在线',
                    '3' => '下架'
                ],
            ],

            'sectionSelect' => [
                'time' => ['start_time']
            ],
            'selectRelatedSearchs' => [
                'details' => [
                    'seller_id' => $this->sellerCode->getEbayCodeWithName(),
                ],
            ],
            'relatedSearchFields' => [
                'details' => [
                    'erp_sku',
                ],
            ]

//
        ];
        return view($this->viewPath . 'index', $response);
    }


    public function productSingleEdit()
    {
        $id = request()->input("id");
        $model = $this->model->find($id);
        $param = request()->input('param');
        $condition= '';
        $specifics = '';
        if($param=='changeSpecifics'){
            $specifics = $this->ebaySpecifics->getSiteCategorySpecifics($model->primary_category, $model->site);
            $condition = $this->ebayCondition->getSiteCategoryCondition($model->primary_category, $model->site);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__, 'Ebay在线数据修改'),
            'product_ids' => $id,
            'param' => $param,
            'model' => $model,
            'condition' => $condition,
            'specifics' => $specifics,
            'shipping' => $this->ebayShipping->where(['site_id' => $model->site])->get(),
            'siteInfo' => $this->ebaySite->where(['site_id' => $model->site])->first(),
        ];
        return view($this->viewPath . 'singleEdit', $response);
    }


    public function productBatchEdit()
    {
        $ids = request()->input("ids");
        $arr = explode(',', $ids);
        $param = request()->input('param');
        $data = $this->model->whereIn("id", $arr)->get();
        $response = [
            'metas' => $this->metas(__FUNCTION__, 'Ebay在线数据修改'),
            'product_ids' => $ids,
            'data' =>$data,
            'param' => $param,
            'description' => $this->descriptionTemplate->get()->lists('name', 'id')

        ];
        return view($this->viewPath . 'batchEdit', $response);
    }
    public function batchUpdate(){
        $param = request()->input("param");
        $ids = request()->input("product_ids");
        $arr = explode(',',$ids);
        $string = '';
        foreach($arr as $id){
            $data = [];
            $product_info = $this->model->find($id);
            $data['item_id'] = $product_info->item_id;
            $data['currency'] = $product_info->currency;
            $data['site_name'] = $product_info->site_name;
            $post = request()->all();
            $account = AccountModel::find($product_info->account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            switch ($param) {
                case 'changeTitle':
                    $data['title'] = isset($post['title']) ? $post['title'] : '';
                    $data['sub_title'] = isset($post['sub_title']) ? $post['sub_title'] : '';
                    $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                    break;
                case 'changeDescription':
                    $description = htmlspecialchars($post['description']);
                    $description_picture = isset($post['description_picture'])?$post['description_picture']:array();
                    $description_id =isset($post['description_id'])?$post['description_id']:'';
                    $title = empty($post['description_title'])?$product_info->title:$post['description_title'];
                    $description = $this->descriptionTemplate->getLastDescription($description_id, $description_picture, $title, $description);
                    $data['description'] = $description;
                    $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                    $data['description'] = htmlspecialchars($description);
                    break;
                case 'changePicture':
                    $picture_details = isset($post['picture_details']) ? $post['picture_details'] : array();
                    $data['picture_details'] = json_encode($picture_details);
                    $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                    break;
                case 'endItem':
                    $data['status'] = '3';
                    $api_result = $channel->endItem($product_info->item_id,$product_info->site); //下架
                    break;
                default :
                    break;
            }
            if ($api_result['is_success']) { //需要更新
                $product_info->update($data);
            }
            $string .=$product_info->item_id.':'.$api_result['info'];

        }
        return redirect($this->mainIndex)->with('alert', $this->alert('danger',$string));

    }
    public function singleUpdate()
    {
        $param = request()->input("param");
        $id = request()->input("product_ids");
        $data = [];
        $product_info = $this->model->find($id);
        $data['item_id'] = $product_info->item_id;
        $data['currency'] = $product_info->currency;
        $data['site_name'] = $product_info->site_name;
        $post = request()->all();
        $account = AccountModel::find($product_info->account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $is_update = true;
        switch ($param) {
            case 'changeSku':

                if (is_array($post['sku'])) {
                    $data['multi_attribute'] = 1;
                    $variation = isset($post['variation']) ? $post['variation'] : array();
                    $variation_new = [];
                    if (!empty($variation)) {
                        foreach ($variation as $key => $var) {
                            if (!empty($var)) {
                                foreach ($post['variation' . $key] as $v) {
                                    if (!empty($v)) {
                                        $variation_new[$var][] = $v;
                                    }
                                }
                            }
                        }
                    }
                    $data['variation_specifics'] = json_encode($variation_new);
                    $variation_picture = isset($post['variation_picture']) ? $post['variation_picture'] : array();
                    $variation_picture_new = [];
                    if (!empty($variation_picture)) {
                        foreach ($variation_picture as $key => $v) {
                            if (!empty($v)) {
                                $variation_picture_new[$post['variation'][0]][$key] = $v;
                            }
                        }
                    }
                    $data['variation_picture'] = json_encode($variation_picture_new);
                    $detail = [];
                    foreach ($post['sku'] as $key => $sku) {
                        $detail[$key]['start_price'] = $post['start_price'][$key];
                        $detail[$key]['sku'] = $sku;
                        $detail[$key]['quantity'] = $post['quantity'][$key];
                    }
                    $data['sku_detail'] = $detail;

                    $is_exist = [];
                    foreach ($product_info->details as $detail) {
                        $is_exist[] = $detail->sku;
                    }
                    $is_delete = [];
                    foreach ($is_exist as $v) {
                        if (!in_array($v, $post['sku'])) {
                            $is_delete[] = $v;
                        }
                    }
                    $data['delete'] = $is_delete;
                    $api_result = $channel->ReviseItem('ReviseFixedPriceItem', $param, $data, $product_info->site);
                    if ($api_result['is_success']) {
                        $is_update = false;
                        $product_info->update(['variation_picture' => $data['variation_picture'], 'variation_picture' => $data['variation_picture'], 'start_price' => $detail[0]['start_price'], 'quantity' => $detail[0]['quantity']]);

                        foreach ($data['sku_detail'] as $sku) {
                            if (in_array($sku['sku'], $is_exist)) { //更新
                                $this->modelDetail->where(['publish_id' => $id, 'sku' => $sku['sku']])->update(['start_price' => $sku['start_price'], 'quantity' => $sku['quantity']]);
                            } else { //新增
                                $sku['publish_id'] = $id;
                                $sku['status'] = 1;
                                $sku['item_id'] = $product_info->item_id;
                                $sku['start_time'] = date('Y-m-d H:i:s', time());
                                $sku['update_time'] = date('Y-m-d H:i:s', time());
                                $this->modelDetail->create($sku);
                            }
                            foreach ($is_delete as $sku) { //删除
                                $this->modelDetail->where(['publish_id' => $id, 'sku' => $sku])->delete();
                            }
                        }
                    }
                } else { //单属性
                    $data['sku'] = $post['sku'];
                    $data['quantity'] = $post['quantity'];
                    $data['start_price'] = $post['start_price'];
                    $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                    if ($api_result['is_success']) {
                        $is_update = false;
                        $product_info->update($data);
                        $this->modelDetail->where(['publish_id' => $id])->update(
                            [
                                'start_price' => $data['start_price'],
                                'quantity' => $data['quantity'],
                                'product_id' => 0,
                                'erp_sku' =>'',
                                'seller_id' =>0,
                                'update_time'=>date('Y-m-d H:i:s',time())
                            ]);
                    }

                }

                break;
            case 'changeTitle':
                $data['title'] = isset($post['title']) ? $post['title'] : '';
                $data['sub_title'] = isset($post['sub_title']) ? $post['sub_title'] : '';
                $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);

                break;
            case 'changeDescription':
                $description = $post['description'];
                $data['description'] = htmlspecialchars($description);
                $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                break;
            case 'changeShipping':
                foreach ($post['shipping'] as $key => $v) {
                    if (!empty($v['ShippingService'])) {
                        $shipping_details['Shipping'][$key]['ShippingService'] = $v['ShippingService'];
                        $shipping_details['Shipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost']) ? round($v['ShippingServiceCost'], 2) : '0.00';
                        $shipping_details['Shipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost']) ? round($v['ShippingServiceAdditionalCost'], 2) : '0.00';
                    }
                }
                foreach ($post['InternationalShipping'] as $key => $v) {
                    if (!empty($v['ShippingService'])) {
                        $shipping_details['InternationalShipping'][$key]['ShippingService'] = $v['ShippingService'];
                        $shipping_details['InternationalShipping'][$key]['ShippingServiceCost'] = !empty($v['ShippingServiceCost']) ? round($v['ShippingServiceCost'], 2) : '0.00';
                        $shipping_details['InternationalShipping'][$key]['ShippingServiceAdditionalCost'] = !empty($v['ShippingServiceAdditionalCost']) ? round($v['ShippingServiceAdditionalCost'], 2) : '0.00';
                        $shipping_details['InternationalShipping'][$key]['ShipToLocation'] = isset($v['ShipToLocation']) ? $v['ShipToLocation'] : array();
                    }
                }
                $shipping_details['ExcludeShipToLocation'] = isset($post['un_ship']) ? $post['un_ship'] : array();
                $data['shipping_details'] = json_encode($shipping_details);

                $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                break;
            case 'changePicture':
                $picture_details = isset($post['picture_details']) ? $post['picture_details'] : array();
                $data['picture_details'] = json_encode($picture_details);
                $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                break;
            case 'changeSpecifics':
                $item_specifics = isset($post['item_specifics']) ? $post['item_specifics'] : array();
                $data['item_specifics'] = json_encode($item_specifics);
                $data['condition_id'] = isset($post['condition_id']) ? $post['condition_id'] : '';
                $data['condition_description'] = isset($post['condition_description']) ? $post['condition_description'] : '';
                $api_result = $channel->ReviseItem('ReviseItem', $param, $data, $product_info->site);
                break;
            default :
                break;
        }

        if ($is_update && $api_result['is_success']) { //需要更新
            $product_info->update($data);
        }
        return redirect($this->mainIndex)->with('alert', $this->alert($api_result['is_success'] ? 'success' : 'danger', $api_result['info']));


    }
}
