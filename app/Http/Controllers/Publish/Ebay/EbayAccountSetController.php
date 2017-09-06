<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-13
 * Time: 10:39
 */
namespace App\Http\Controllers\Publish\Ebay;

use Tool;
use App\Http\Controllers\Controller;
use App\Models\Publish\Ebay\EbayAccountSetModel;
use App\Models\PaypalsModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;



class EbayAccountSetController extends Controller
{
    public function __construct(EbayAccountSetModel $accountSet, EbayPublishProductModel $ebayPublish)
    {
        $this->model = $accountSet;
        $this->mainIndex = route('ebayAccountSet.index');
        $this->mainTitle = 'Ebay账号设置';
        $this->viewPath = 'publish.ebay.accountSet.';
        $this->ebayPublish = $ebayPublish;


    }


    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'account' => $this->ebayPublish->getChannelAccount(),
            'paypal' =>PaypalsModel::where(['is_enable'=>1])->get()->lists('paypal_email_address', 'id'),
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
            'account' => $this->ebayPublish->getChannelAccount(),
            'paypal' =>PaypalsModel::where(['is_enable'=>1])->get()->lists('paypal_email_address', 'id'),
        ];
        return view($this->viewPath . 'edit', $response);
    }


    public function store(){
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $data['currency'] = json_encode($data['currency']);
        $model = $this->model->create($data);
        $this->eventLog(request()->user()->id, '数据新增', serialize($model));
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '新增成功'));
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
        $data['currency'] = json_encode($data['currency']);
        $model->update($data);
        $to = serialize($model);
        $this->eventLog(request()->user()->id, '数据更新', $to, $from);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '数据更新成功'));
    }
}