<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/9/27
 * Time: 上午9:59
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\Order\UnpaidOrderModel;
use App\Models\UserModel;

class UnpaidOrderController extends Controller
{
    public function __construct(UnpaidOrderModel $unpaidOrder)
    {
        $this->model = $unpaidOrder;
        $this->mainIndex = route('unpaidOrder.index');
        $this->mainTitle = '未付款订单';
        $this->viewPath = 'order.unpaidOrder.';
    }

    public function create()
    {
        $user = request()->user();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
            'users' => $user,
        ];

        return view($this->viewPath . 'create', $response);
    }

    public function index()
    {
        request()->flash();
        $model = $this->model->where('customer_id', request()->user()->id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($model),
        ];
        return view($this->viewPath . 'index', $response);
    }

    public function edit($id)
    {
        $hideUrl = $_SERVER['HTTP_REFERER'];
        $model = $this->model->find($id);
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'channels' => ChannelModel::all(),
            'users' => UserModel::all(),
            'hideUrl' => $hideUrl,
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(request()->all());
        $to = json_encode($model);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据更新', $to, $from);

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

}