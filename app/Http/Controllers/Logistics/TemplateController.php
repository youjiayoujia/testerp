<?php
/**
 * 面单模版控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/13
 * Time: 下午2:41
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\TemplateModel;
use App\Models\LogisticsModel;
use App\Models\PackageModel;

class TemplateController extends Controller
{
    public function __construct(TemplateModel $template)
    {
        $this->model = $template;
        $this->mainIndex = route('logisticsTemplate.index');
        $this->mainTitle = '面单模版';
        $this->viewPath = 'logistics.template.';
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

    /**
     * 跳转面单模版页面
     */
    public function view($id)
    {
        $model = $this->model->find($id);

        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];

        return view($this->viewPath . 'tpl.' . explode('.', $model->view)[0], $response);
    }

    //面单确认
    public function confirm()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__, '面单确认'),
            'logistics' => LogisticsModel::all(),
        ];

        return view($this->viewPath . 'confirm', $response);
    }

    /**
     * 获取物流方式信息
     */
    public function ajaxLogistics()
    {
        if (request()->ajax()) {
            $logistics = trim(request()->input('logistics_id'));
            $buf = LogisticsModel::where('name', 'like', '%' . $logistics . '%')->get();
            $total = $buf->count();
            $arr = [];
            foreach ($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if ($total) {
                return json_encode(['results' => $arr, 'total' => $total]);
            } else {
                return json_encode(false);
            }
        }

        return json_encode(false);
    }

    //确认
    public function preview()
    {
        $packageId = request()->input('package_id');
        $logisticsId = request()->input('logistics_id');
        $model = PackageModel::find($packageId);
        $logistics = LogisticsModel::find($logisticsId);
        $model->logistics = $logistics;
        $view = $logistics->template->view;
        $view = explode('.', $view)[0];
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];

        return view('logistics.template.tpl.' . $view, $response);
    }

    //面单确认
    public function queren()
    {
        $logistics_id = request()->input('logistics_id');
        $logistics = LogisticsModel::find($logistics_id);
        $logistics->update(['is_confirm' => '1']);

        return 1;
    }

    /**
     * 保存
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $model = $this->model->create(request()->all());
//        $path = '../app/Views/logistics/template/tpl/';
//        fopen($path . request()->all()['view'], 'w');
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        return redirect($this->mainIndex);
    }

}