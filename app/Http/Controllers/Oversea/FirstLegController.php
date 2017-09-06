<?php
/**
 * 海外仓头程物流控制器
 *
 * 2016-12.05
 * @author: MC<178069409>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\FirstLeg\FirstLegModel;
use App\Models\WarehouseModel;

class FirstLegController extends Controller
{
    public function __construct(FirstLegModel $firstLeg)
    {
        $this->model = $firstLeg;
        $this->mainIndex = route('firstLeg.index');
        $this->mainTitle = '海外仓头程物流';
        $this->viewPath = 'oversea.firstLeg.';
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
            'warehouses' => WarehouseModel::where(['type' => 'fbaLocal', 'is_available' => '1'])->get(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'forms' => $model->forms()->orderBy('weight_from')->get(),
        ];
        return view($this->viewPath . 'show', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->create(request()->all());
        $arr = request('arr');
        foreach($arr['weight_from'] as $key => $single) {
            $model->forms()->create(['weight_from' => $single, 'weight_to' => $arr['weight_to'][$key], 'cost' => $arr['price'][$key]]);
        }
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        return redirect($this->mainIndex);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
        foreach($model->forms as $form) {
            $form->forceDelete();
        }
        $arr = request('arr');
        foreach($arr['weight_from'] as $key => $single) {
            $model->forms()->create(['weight_from' => $single, 'weight_to' => $arr['weight_to'][$key], 'cost' => $arr['price'][$key]]);
        }
        $to = json_encode($model);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据更新', $to, $from);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '操作成功.'));
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        foreach($model->forms as $form) {
            $form->delete();
        }
        $model->destroy($id);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '操作成功.') );
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'warehouses' => WarehouseModel::where(['type' => 'fbaLocal', 'is_available' => '1'])->get(),
            'forms' => $model->forms()->orderBy('weight_from')->get(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function sectionAdd()
    {
        $current = request('current');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'current' => $current,
        ];

        return view($this->viewPath.'add', $response);
    }
}