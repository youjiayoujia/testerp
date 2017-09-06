<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DataList;
use App\Models\Event\CategoryModel;
use App\Models\Event\ChildModel;

abstract class Controller extends BaseController
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected $model;
    protected $viewPath;
    protected $mainIndex;
    protected $mainTitle;

    public function metas($action, $title = null)
    {
        $metas = [
            'mainIndex' => $this->mainIndex,
            'mainTitle' => $this->mainTitle,
            'title' => $title ? $title : $this->mainTitle . config('setting.titles.' . $action),
        ];
        return $metas;
    }

    public function calcTwoArr(&$a,&$b)
    {
        foreach($a as $key => $value) {
            if(array_key_exists($key, $b)) {
                if($this->valueEqual($value,$b[$key])) {
                    unset($a[$key]);
                    unset($b[$key]);
                } else {
                    if(getType($value) == getType($b[$key]) && is_array($value)) {
                        $this->calcTwoArr($a[$key],$b[$key]);
                    }
                }
            }
        }
    }

    public function valueEqual($c,$d)
    {
        if(getType($c) == getType($d)) {
            if(!is_array($c)) {
                if($c == $d) {
                    return true;
                } else {
                    return false;
                }
            } else {
                foreach($c as $key => $value) {
                    if(array_key_exists($key, $d)) {
                        if(!$this->valueEqual($value, $d[$key])) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
                return true;
            }
        } else {
            return false;
        }
    }

    public function eventLog($user, $content = '', $to = '', $from = '')
    {
        $modelName = $this->model->table;
        if($modelName) {
            $category = CategoryModel::where('model_name', $modelName)->first();
            if(!$category) {
                $category = CategoryModel::create(['model_name' => $modelName]);
            }
            $category->child()->create(['type_id' => ($to ? json_decode($to)->id : ''), 'what' => $content, 'when' => date('Y-m-d H:i:s', time()), 'to_arr' => $to, 'from_arr' => $from, 'who' => $user]);
        }
    }

    public function alert($type, $content)
    {
        $response = ['type' => $type, 'content' => $content];
        return view('common.alert', $response)->render();
    }

    //查询筛选的数据
    public function allList($model, $list = null, $fields = ['*'], $pageSize = null)
    {
        $list = $list ? $list : $model;
        if (request()->has('mixedSearchFields')) {
            $relateds = request()->input('mixedSearchFields');
            foreach ($relateds as $type => $related) {
                switch ($type) {
                    case 'relatedSearchFields':
                        foreach ($related as $relation_ship => $name_arr) {
                            foreach ($name_arr as $k => $name) {
                                $name = trim($name);
                                if ($name != '') {
                                    $list = $list->whereHas($relation_ship, function ($query) use ($k, $name) {
                                        $query = $query->where($k, $name);
                                    });
                                }
                            }
                        }
                        break;
                    case 'doubleRelatedSearchFields':
                        foreach ($related as $relation_ship1 => $value1) {
                            foreach ($value1 as $relation_ship2 => $value2) {
                                foreach ($value2 as $key => $name) {
                                    $name = trim($name);
                                    if ($name != '') {
                                        $list = $list->whereHas($relation_ship1,
                                            function ($query) use ($relation_ship2, $name, $key) {
                                                $query = $query->wherehas($relation_ship2,
                                                    function ($query1) use ($name, $key) {
                                                        $query1 = $query1->where($key, $name);
                                                    });
                                            });
                                    }
                                }
                            }
                        }
                        break;
                    case 'doubleRelatedSelectedFields':
                        foreach ($related as $relation_ship1 => $value1) {
                            foreach ($value1 as $relation_ship2 => $value2) {
                                foreach ($value2 as $key => $name) {
                                    $name = trim($name);
                                    if ($name != '') {
                                        $list = $list->whereHas($relation_ship1,
                                            function ($query) use ($relation_ship2, $name, $key) {
                                                $query = $query->wherehas($relation_ship2,
                                                    function ($query1) use ($name, $key) {
                                                        $query1 = $query1->where($key, $name);
                                                    });
                                            });
                                    }
                                }
                            }
                        }
                        break;
                    case 'filterFields':
                        foreach ($related as $key => $value3) {
                            $value3 = trim($value3);
                            if ($value3) {
                                $list = $list->where($key, $value3);
                            }
                        }
                        break;
                    case 'filterSelects':
                        foreach ($related as $key => $value2) {
                            $value2 = trim($value2);
                            if ($value2||$value2=='0') {
                                $list = $list->where($key, $value2);
                            }
                        }
                        break;
                    case 'selectRelatedSearchs':
                        foreach ($related as $relation_ship => $contents) {
                            foreach ($contents as $name => $single) {
                                $single = trim($single);
                                if ($single != '') {
                                    $list = $list->whereHas($relation_ship, function ($query) use ($name, $single) {
                                        $query = $query->where($name, $single);
                                    });
                                }
                            }
                        }
                        break;
                    case 'sectionSelect':
                        foreach ($related as $kind => $content) {
                            if(!empty($content['begin']) && !empty($content['end'])) {
                                $list = $list->whereBetween($kind, [str_replace('/', '-', trim($content['begin'])), str_replace('/', '-', trim($content['end']))]);
                            }
                            if(empty($content['begin']) && !empty($content['end'])) {
                                $list = $list->where($kind, '<', str_replace('/', '-', trim($content['end'])));
                            }
                            if(!empty($content['begin']) && empty($content['end'])) {
                                $list = $list->where($kind, '>', str_replace('/', '-', trim($content['begin'])));
                            }
                        }
                        break;
                }
            }
        }

        return $list;
    }

    public function autoList($model, $list = null, $fields = ['*'], $pageSize = null, $level = '', $preload = '')
    {
        $list = $list ? $list : $model;
        if (request()->has('keywords')) {
            $keywords = request()->input('keywords');
            $searchFields = $model->searchFields;
            $list = $list->where(function ($query) use ($keywords, $searchFields) {
                foreach ($searchFields as $key => $searchField) {
                    $query = $query->orWhere($key, 'like', '%'.trim($keywords).'%');
                }
            });
        }
        if (request()->has('mixedSearchFields')) {
            $relateds = request()->input('mixedSearchFields');
            foreach ($relateds as $type => $related) {
                switch ($type) {
                    case 'relatedSearchFields':
                        foreach ($related as $relation_ship => $name_arr) {
                            foreach ($name_arr as $k => $name) {
                                $name = trim($name);
                                if ($name != '') {
                                    if(!$level) {
                                        $list = $list->whereHas($relation_ship, function ($query) use ($k, $name) {
                                            $query = $query->where($k, $name);
                                        }); 
                                    } else {
                                        $list = $model->relatedGet($list, $relation_ship, $k, $name);
                                    }
                                }
                            }
                        }
                        break;
                    case 'doubleRelatedSearchFields':
                        foreach ($related as $relation_ship1 => $value1) {
                            foreach ($value1 as $relation_ship2 => $value2) {
                                foreach ($value2 as $key => $name) {
                                    $name = trim($name);
                                    if ($name != '') {
                                        $list = $list->whereHas($relation_ship1,
                                            function ($query) use ($relation_ship2, $name, $key) {
                                                $query = $query->wherehas($relation_ship2,
                                                    function ($query1) use ($name, $key) {
                                                        $query1 = $query1->where($key, $name);
                                                    });
                                            });
                                    }
                                }
                            }
                        }
                        break;
                    case 'doubleRelatedSelectedFields':
                        foreach ($related as $relation_ship1 => $value1) {
                            foreach ($value1 as $relation_ship2 => $value2) {
                                foreach ($value2 as $key => $name) {
                                    $name = trim($name);
                                    if ($name != '') {
                                        $list = $list->whereHas($relation_ship1,
                                            function ($query) use ($relation_ship2, $name, $key) {
                                                $query = $query->wherehas($relation_ship2,
                                                    function ($query1) use ($name, $key) {
                                                        $query1 = $query1->where($key, $name);
                                                    });
                                            });
                                    }
                                }
                            }
                        }
                        break;
                    case 'filterFields':
                        foreach ($related as $key => $value3) {
                            $value3 = trim($value3);
                            if ($value3) {
                                $list = $list->where($model->table.'.'.$key, $value3);
                            }
                        }
                        break;
                    case 'filterSelects':
                        foreach ($related as $key => $value2) {
                            $value2 = trim($value2);
                            if ($value2||$value2=='0') {
                                $list = $list->where($model->table.'.'.$key, $value2);
                            }
                        }
                        break;
                    case 'selectRelatedSearchs':
                        foreach ($related as $relation_ship => $contents) {
                            foreach ($contents as $name => $single) {
                                $single = trim($single);
                                if ($single != '') {
                                    if(!$level) {
                                        $list = $list->whereHas($relation_ship, function ($query) use ($name, $single) {
                                            $query = $query->where($name, $single);
                                        });
                                    } else {
                                        $list = $model->relatedGet($list, $relation_ship, $name, $single);
                                    }
                                }
                            }
                        }
                        break;
                    case 'sectionSelect':
                        foreach ($related as $kind => $content) {
                            if(!empty($content['begin']) && !empty($content['end'])) {
                                $list = $list->whereBetween($model->table.'.'.$kind, [str_replace('/', '-', trim($content['begin'])), str_replace('/', '-', trim($content['end']))]);
                            }
                            if(empty($content['begin']) && !empty($content['end'])) {
                                $list = $list->where($model->table.'.'.$kind, '<', str_replace('/', '-', trim($content['end'])));
                            }
                            if(!empty($content['begin']) && empty($content['end'])) {
                                $list = $list->where($model->table.'.'.$kind, '>', str_replace('/', '-', trim($content['begin'])));
                            }
                        }
                        break;
                    case 'sectionGanged':
                        foreach ($related as $kind => $content) {
                            if($kind == 'first') {
                                foreach ($content as $relation_ship1 => $value1) {
                                    foreach ($value1 as $relation_ship2 => $value2) {
                                        foreach ($value2 as $key => $name) {
                                            $name = trim($name);
                                            if ($name != '') {
                                                $list = $list->whereHas($relation_ship1,
                                                    function ($query) use ($relation_ship2, $name, $key) {
                                                        $query = $query->wherehas($relation_ship2,
                                                            function ($query1) use ($name, $key) {
                                                                $query1 = $query1->where($key, $name);
                                                            });
                                                    });
                                            }
                                        }
                                    }
                                }
                            }
                            if($kind == 'second') {
                                foreach ($content as $key => $value2) {
                                    $value2 = trim($value2);
                                    if ($value2||$value2=='0') {
                                        $list = $list->where($key, $value2);
                                    }
                                }
                            }
                        }
                        break;

                    case 'sectionGangedDouble':
                        foreach ($related as $kind => $content) {
                            if($kind == 'first') {
                                foreach ($content as $relation_ship1 => $value1) {
                                    foreach ($value1 as $relation_ship2 => $value2) {
                                        foreach ($value2 as $key => $name) {
                                            $name = trim($name);
                                            if ($name != '') {
                                                $list = $list->whereHas($relation_ship1,
                                                    function ($query) use ($relation_ship2, $name, $key) {
                                                        $query = $query->wherehas($relation_ship2,
                                                            function ($query1) use ($name, $key) {
                                                                $query1 = $query1->where($key, 'like', '%' . $name . '%');
                                                            });
                                                    });
                                            }
                                        }
                                    }
                                }
                            }
                            if($kind == 'second') {
                                foreach ($content as $relation_ship1 => $value1) {
                                    foreach ($value1 as $key => $value2) {
                                        $value2 = trim($value2);
                                        if ($value2||$value2=='0') {
                                            $list = $list->whereHas($relation_ship1, function($query) use ($value2){
                                                $query->where('c_name', 'like', '%'.$value2.'%');
                                            });
                                        }
                                    }
                                }
                            }
                        }
                        break;
                }
            }
        }
        if (request()->has('filters')) {
            foreach (DataList::filtersDecode(request()->input('filters')) as $filter) {
                $list = $list->where($filter['field'], $filter['oprator'], $filter['value']);
            }
        }
        if (request()->has('sorts')) {
            if($list->first()) {
                foreach (DataList::sortsDecode(request()->input('sorts')) as $sort) {
                    $list = $list->orderBy($list->first()->table.'.'.$sort['field'], $sort['direction']);
                }
            }
        } else {
            if($list->first()) {
                $list = $list->orderBy($model->table.'.id', 'desc');
            }
        }
        if (!$pageSize) {
            $pageSize = request()->has('pageSize') ? request()->input('pageSize') : config('setting.pageSize');
        }
        if($preload) {
            foreach($preload as $single) {
                $list = $list->with($single);
            }
        }
        return $list->paginate($pageSize, $fields);
    }

    /**
     * 列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
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
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
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
        $from = json_encode($model);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $model->update(request()->all());
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
        $model->destroy($id);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '操作成功.') );
    }
}
