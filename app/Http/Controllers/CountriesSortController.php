<?php
/**
 * 国家分类控制器
 * 处理国家分类相关的Request与Response
 *
 * @author: MC<178069409>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountriesModel;
use App\Models\CountriesSortModel;

class CountriesSortController extends Controller
{
    public function __construct(CountriesSortModel $sort)
    {
        $this->model = $sort;
        $this->mainIndex = route('countriesSort.index');
        $this->mainTitle = '国家分类';
        $this->viewPath = 'countries.sort.';
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
            'countries' => $model->countries,
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
            'countries' => CountriesModel::where('parent_id', '0')->get(),
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
        foreach(request('country_id') as $single) {
            $country = CountriesModel::find($single);
            $country->parent_id = $model->id;
            $country->save();
        }

        return redirect($this->mainIndex);
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
            'countries' => CountriesModel::all(),
            'owns' => $model->countries,
        ];
        return view($this->viewPath . 'edit', $response);
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
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(['name' => request('name')]);
        $countries = $model->countries;
        foreach($countries as $country)
        {
            $country->parent_id = 0;
            $country->save();
        }
        foreach(request('country_id') as $single)
        {
            $country = CountriesModel::find($single);
            $country->parent_id = $model->id;
            $country->save();
        }
        return redirect($this->mainIndex);
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
        $countries = $model->countries;
        foreach($countries as $country)
        {
            $country->parent_id = 0;
            $country->save();
        }
        $model->destroy($id);
        return redirect($this->mainIndex);
    }
}