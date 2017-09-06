<?php
/**
 * 收款信息控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/5
 * Time: 下午4:54
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CollectionInfoModel;

class CollectionInfoController extends Controller
{
    public function __construct(CollectionInfoModel $collectionInfo)
    {
        $this->model = $collectionInfo;
        $this->mainIndex = route('logisticsCollectionInfo.index');
        $this->mainTitle = '收款信息';
        $this->viewPath = 'logistics.collectionInfo.';
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