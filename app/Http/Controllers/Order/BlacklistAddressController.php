<?php
/**
 * 黑名单地址验证
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/12/26
 * Time: 下午2:58
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\BlacklistAddressModel;
use App\Models\UserModel;

class BlacklistAddressController extends Controller
{
    public function __construct(BlacklistAddressModel $blacklistAddress)
    {
        $this->model = $blacklistAddress;
        $this->mainIndex = route('blacklistAddress.index');
        $this->mainTitle = '风控订单地址验证';
        $this->viewPath = 'order.blacklist.address.';
    }

    /**
     * 编辑
     */
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
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(request()->all());
        $to = json_encode($model);
        $this->eventLog($userName->name, '数据更新,id=' . $id, $to, $from);

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

}