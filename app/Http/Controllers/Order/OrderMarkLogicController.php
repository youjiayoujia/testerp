<?php
/**
 * 标记发货规则
 * User: lilifeng
 * Date: 2016-07-13
 * Time: 15:46
 */
namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\OrderMarkLogicModel;
use App\Models\ChannelModel;

class OrderMarkLogicController extends Controller
{
    public function __construct(OrderMarkLogicModel $orderMarkLogic)
    {
        $this->model = $orderMarkLogic;
        $this->mainIndex = route('orderMarkLogic.index');
        $this->mainTitle = '标记发货规则';
        $this->viewPath = 'order.orderMarkLogic.';
    }

    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'order_status' => config('order.status'),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'order_status' => config('order.status'),
            'channels' => ChannelModel::all(),
            'hideUrl' => $hideUrl,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'order_status' => config('order.status')
        ];
        return view($this->viewPath . 'create', $response);
    }

    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels' => ChannelModel::all(),
            'order_status' => config('order.status')
        ];
        return view($this->viewPath . 'show', $response);
    }

    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $data['user_id'] = request()->user()->id;
        $data['order_status'] = json_encode($data['order_status']);
        $model = $this->model->create($data);
        $this->eventLog(request()->user()->id, '数据新增', serialize($model));
        return redirect($this->mainIndex);
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
        $data['order_status'] = json_encode($data['order_status']);
        $data['wish_upload_tracking_num'] = isset($data['wish_upload_tracking_num']) ? $data['wish_upload_tracking_num'] : 0;
        $model->update($data);
        $to = serialize($model);
        $this->eventLog(request()->user()->id, '数据更新', $to, $from);
        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

}