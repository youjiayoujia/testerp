<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-19
 * Time: 13:51
 */
namespace App\Http\Controllers\Publish\Ebay;
use Channel;
use Tool;
use App\Http\Controllers\Controller;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Ebay\EbayStoreCategorySetModel;
use App\Models\Publish\Ebay\EbayStoreCategoryModel;
use App\Models\Publish\Ebay\EbayDescriptionTemplateModel;
use App\Models\CatalogModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;

class EbayStoreCategoryController extends Controller
{
    public function __construct(EbayStoreCategorySetModel $categorySet,EbayStoreCategoryModel $storeCategory, CatalogModel $erpCategory,EbayPublishProductModel $ebayPublish)
    {
        $this->model = $categorySet;
        $this->mainIndex = route('ebayStoreCategory.index');
        $this->mainTitle = 'Ebay店铺分类设置';
        $this->viewPath = 'publish.ebay.storeCategory.';
        $this->storeCategory = $storeCategory;
        $this->erpCategory =  $erpCategory;
        $this->ebayPublish = $ebayPublish;

    }


    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
            'account' => $this->ebayPublish->getChannelAccount(),
        ];
        return view($this->viewPath . 'index', $response);
    }


    public function create()
    {
        $account_category = [];
        $account =  $this->ebayPublish->getChannelAccount();
        foreach($account as $key=> $ac){
            $account_category[$key]=$this->storeCategory->getALLCategory($key);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'erpCategory' => $this->erpCategory->get()->lists('name', 'id'),
            'account' => $account,
            'template' =>EbayDescriptionTemplateModel::get()->lists('name', 'id'),
            'account_category'=>$account_category

        ];
        return view($this->viewPath . 'create', $response);
    }

    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $i=1;
        foreach($data['category_description'] as $key=>$value){
            if($i==1){
                if(!empty($value['store_category'])){
                    $store_category_name   = $this->storeCategory->where('store_category',$value['store_category'])->first()->store_category_name;
                }else{
                    $store_category_name = '';
                }
                $description_id = $value['description_id'];
            }else{
                if(!empty($store_category_name)&&empty($value['store_category'])){
                    $data['category_description'][$key]['store_category'] = $this->storeCategory->where('store_category_name',$store_category_name)->first()->store_category;
                }
                if(!empty($description_id)&&empty($value['description_id'])){
                    $data['category_description'][$key]['description_id'] = $description_id;
                }

            }
            $i++;
        }

        $data['category_description'] = json_encode($data['category_description']);
        $model = $this->model->create($data);
        $this->eventLog(request()->user()->id, '数据新增', serialize($model));
        return redirect($this->mainIndex);
    }


    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $account_category = [];
        $account =  $this->ebayPublish->getChannelAccount();
        foreach($account as $key=> $ac){
            $account_category[$key]=$this->storeCategory->getALLCategory($key);
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'erpCategory' => $this->erpCategory->get()->lists('name', 'id'),
            'account' => $account,
            'template' =>EbayDescriptionTemplateModel::get()->lists('name', 'id'),
            'account_category'=>$account_category
        ];
        return view($this->viewPath . 'edit', $response);
    }


    public function update($id)
    {
        $model = $this->model->find($id);
        $from = serialize($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $data = request()->all();
        $i=1;
        foreach($data['category_description'] as $key=>$value){
            if($i==1){
                if(!empty($value['store_category'])){
                    $store_category_name   = $this->storeCategory->where('store_category',$value['store_category'])->first()->store_category_name;
                }else{
                    $store_category_name = '';
                }
                $description_id = $value['description_id'];
            }else{
                if(!empty($store_category_name)&&empty($value['store_category'])){
                    $data['category_description'][$key]['store_category'] = $this->storeCategory->where('store_category_name',$store_category_name)->first()->store_category;
                }
                if(!empty($description_id)&&empty($value['description_id'])){
                    $data['category_description'][$key]['description_id'] = $description_id;
                }

            }
            $i++;
        }
        $data['category_description'] = json_encode($data['category_description']);
        $model->update($data);
        $to = serialize($model);
        $this->eventLog(request()->user()->id, '数据更新', $to, $from);
        return redirect($this->mainIndex);
    }


    public function  ajaxUpdateStoreCategory(){
        $account_id = request()->input('account_id');
        $account = AccountModel::findOrFail($account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->getStoreCategory();
        if($result){
            $this->storeCategory->where('account_id',$account_id)->delete();
            foreach($result as $re){
                $re['account_id'] = $account_id;
                $this->storeCategory->create($re);
            }
            echo 1;
            die;
        }else{
            echo 2;
            die;
        }

    }

}