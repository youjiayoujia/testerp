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
use App\Models\Logistics\CatalogModel;
use App\Models\UserModel;

class CatalogController extends Controller
{
    public function __construct(CatalogModel $catalog)
    {
        $this->model = $catalog;
        $this->mainIndex = route('logisticsCatalog.index');
        $this->mainTitle = '物流分类';
        $this->viewPath = 'logistics.catalog.';
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