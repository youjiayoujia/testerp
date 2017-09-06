<?php
/**
 * 物流分类控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/11
 * Time: 下午2:12
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\ChannelNameModel;
use App\Models\ChannelModel;

class ChannelNameController extends Controller
{
    public function __construct(ChannelNameModel $channelName)
    {
        $this->model = $channelName;
        $this->mainIndex = route('logisticsChannelName.index');
        $this->mainTitle = '渠道回传编码';
        $this->viewPath = 'logistics.channelName.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'channels' => ChannelModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
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
            'channels' => ChannelModel::all(),
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