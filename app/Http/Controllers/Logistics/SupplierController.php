<?php
/**
 * 物流商控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/10
 * Time: 下午3:42
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\CollectionInfoModel;
use App\Models\Logistics\SupplierModel;
use App\Models\UserModel;

class SupplierController extends Controller
{
    public function __construct(SupplierModel $supplier)
    {
        $this->model = $supplier;
        $this->mainIndex = route('logisticsSupplier.index');
        $this->mainTitle = '物流商';
        $this->viewPath = 'logistics.supplier.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'collectionInfos' => CollectionInfoModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $model = $this->model->createSupplier($data, request()->file('credentials'));
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        return redirect($this->mainIndex);
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
            'collectionInfos' => CollectionInfoModel::all(),
            'hideUrl' => $hideUrl,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function update($id)
    {
        $model = $this->model->find($id);
        $userName = UserModel::find(request()->user()->id);
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $data = request()->all();
        $this->validate(request(), $this->model->rules('update'));
        $this->model->updateSupplier($id, $data, request()->file('credentials'));
        $to = json_encode($this->model->find($id));
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
    }

}