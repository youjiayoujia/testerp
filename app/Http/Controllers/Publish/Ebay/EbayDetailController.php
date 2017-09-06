<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-01
 * Time: 13:52
 */
namespace App\Http\Controllers\Publish\Ebay;
use Channel;
use App\Models\Channel\AccountModel;
use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\Publish\Ebay\EbayShippingModel;
use App\Models\Publish\Ebay\EbayCategoryModel;


class EbayDetailController extends Controller
{
    public function __construct(EbaySiteModel $ebaySite,EbayShippingModel $ebayShipping,EbayCategoryModel $ebayCategory)
    {
        $this->model = $ebaySite;
        $this->mainIndex = route('ebayDetail.index');
        $this->mainTitle = 'ebay站点信息';
        $this->viewPath = 'publish.ebay.site.';
        $this->ebayShipping = $ebayShipping;
        $this->ebayCategory= $ebayCategory;
    }



    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$this->model->orderBy('id', 'asc')),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
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
            'shipping_last_time' =>isset($this->ebayShipping->where('site_id',$model->site_id)->first()->updated_at)?$this->ebayShipping->where('site_id',$model->site_id)->first()->updated_at:'',
            'category_last_time' =>isset($this->ebayCategory->where('site',$model->site_id)->first()->updated_at)?$this->ebayCategory->where('site',$model->site_id)->first()->updated_at:'',


        ];
        $response['metas']['title']=$model->site.'站点信息更新';
        return view($this->viewPath . 'edit', $response);
    }




    /*
     * 获取可用站点
     */
    public function getEbaySite(){
        $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbaySite();
        if($result){
            foreach($result as $re){
                $siteInfo=  $this->model->where('site_id',$re['site_id'])->first();
               if(empty($siteInfo)){ //ADD
                   $this->model->create($re);
               }else{//update
                   $this->model->where('id',$siteInfo->id)->update($re);
               }
            }

            $currency = config('ebaysite.init_currency');
            foreach($currency as $key=>$value){
                $this->model->where('site_id',$key)->update(['currency'=>$value]);
            }
        }else{
            echo '同步失败';
        }
    }
    /*
     * 退货政策
     */
    public function getEbayReturnPolicy($site){
        $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbayReturnPolicy($site);
        if($result){
            $siteInfo = $this->model->where('site_id',$site)->first();
            if(!empty($siteInfo)){
                $this->model->where('site_id',$site)->update($result);
                return true;

            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*
     * 获得对应站点的运输方式
     */
    public function getEbayShipping($site){
        $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getEbayShipping($site);
        if($result){
            foreach($result as $ship){
                $ship['site_id'] = $site;
                $shipInfo = $this->ebayShipping->where('site_id',$site)->where('shipping_service_id',$ship['shipping_service_id'])->first();
                if(!empty($shipInfo)){
                    $this->ebayShipping->where('id',$shipInfo->id)->update($ship);
                }else{
                    $this->ebayShipping->create($ship);
                }
            }
            return true;
        }else{
            return false;
        }
    }

    /*
     * 获取对应站点的分类
     */
    public function getEbayCategory($site){
        $account = AccountModel::where('account',config('ebaysite.default_account'))->first();
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        EbayCategoryModel::where('site',$site)->delete(); //全部删除
        $result = $channel->getEbayCategoryList(1,'',$site);
        if($result){
            foreach($result as $re){
                EbayCategoryModel::create($re);
            }
        }else{
            return false;
        }
        $category_result = EbayCategoryModel::where(['site'=>$site,'category_level'=>1])->get();
        EbayCategoryModel::where('site',$site)->delete(); //再把一级分类删除了
        foreach($category_result as $category ){
            $result = $channel->getEbayCategoryList(6,$category->category_id,$site);
            if($result){
                foreach($result as $re){
                    EbayCategoryModel::create($re);
                }
            }
        }
        return true;
    }

    /**
     * ajax 更新站点信息
     */
   public function ajaxUpdate()
   {
       $result =false;
       $site = request()->input('site');
       $type = request()->input('type');
       switch ($type) {
           case 'returns':
               $result = $this->getEbayReturnPolicy($site);
               break;
           case 'shipping':
               $result = $this->getEbayShipping($site);
               break;
           case 'category':
               $result = $this->getEbayCategory($site);
               break;
       }
       if($result){
           echo '1';
       }else{
           echo '2';
       }

   }

    public function ajaxIsUse(){
        $site = request()->input('site');
        $value = request()->input('value');
        $this->model->where('site_id',$site)->update(array('is_use'=>$value));
        echo json_encode('设置完成');
    }



}

