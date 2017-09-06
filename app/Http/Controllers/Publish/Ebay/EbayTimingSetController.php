<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-28
 * Time: 13:24
 */
namespace App\Http\Controllers\Publish\Ebay;

use App\Http\Controllers\Controller;
use App\Models\Publish\Ebay\EbayTimingSetModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;


class EbayTimingSetController extends Controller
{
    public function __construct(EbayTimingSetModel $ebayTiming,EbayPublishProductModel $ebayPublish)
    {
        $this->model = $ebayTiming;
        $this->mainIndex = route('ebayTiming.index');
        $this->mainTitle = 'Ebay曝光规则';
        $this->viewPath = 'publish.ebay.timingSet.';
        $this->ebayPublish = $ebayPublish;
    }

    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }


    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'account' => $this->ebayPublish->getChannelAccount(),

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
        ];
        return view($this->viewPath . 'edit', $response);
    }
    //edit
}