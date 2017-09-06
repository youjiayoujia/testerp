<?php
/**
 * 物流分区控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/10/23
 * Time: 上午12:49
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\CountriesModel;
use App\Models\Logistics\PartitionModel;
use App\Models\Logistics\PartitionSortModel;
use App\Models\UserModel;

class PartitionController extends Controller
{
    public function __construct(PartitionModel $partition)
    {
        $this->model = $partition;
        $this->mainIndex = route('logisticsPartition.index');
        $this->mainTitle = '物流分区';
        $this->viewPath = 'logistics.partition.';
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
            'partitionSorts' => $model->partitionSorts,
        ];
        return view($this->viewPath . 'show', $response);
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
            'countries' => CountriesModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
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
        $countries = explode(',', request('country_id'));
        $remark = '';
        foreach($countries as $country) {
            $obj = CountriesModel::where('cn_name', $country)->first();
            if($obj) {
                $data['country_id'] = $obj->id;
                $data['logistics_partition_id'] = $model->id;
                PartitionSortModel::create($data);
            }else {
                $remark = $remark . $country . ' ';
            }
        }
        $model = $this->model->with('partitionSorts')->find($model->id);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        if($remark == null) {
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功'));
        }else {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $remark . '未匹配上'));
        }
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
        $arr = [];
        foreach ($model->partitionSorts as $partitionSort) {
            $arr[] = $partitionSort->country_id;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'countries' => CountriesModel::all(),
            'arr' => $arr,
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
        $model->update(['name' => request('name')]);
        $partitionSorts = $model->partitionSorts;
        foreach($partitionSorts as $partitionSort) {
            $partitionSort->delete();
        }
        foreach(request('country_id') as $value) {
            $data['country_id'] = $value;
            $data['logistics_partition_id'] = $id;
            PartitionSortModel::create($data);
        }
        $model = $this->model->with('partitionSorts')->find($id);
        $to = json_encode($model);
        $this->eventLog($userName->name, '数据更新,id='.$id, $to, $from);

        $url = request()->has('hideUrl') ? request('hideUrl') : $this->mainIndex;
        return redirect($url)->with('alert', $this->alert('success', '编辑成功.'));
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
        $partitionSorts = $model->partitionSorts;
        foreach($partitionSorts as $partitionSort)
        {
            $partitionSort->delete();
        }
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}