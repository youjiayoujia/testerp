<?php
/**
 * 回邮模版控制器
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/6/12
 * Time: 上午9:08
 */

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\EmailTemplateModel;

class EmailTemplateController extends Controller
{
    public function __construct(EmailTemplateModel $emailTemplate)
    {
        $this->model = $emailTemplate;
        $this->mainIndex = route('logisticsEmailTemplate.index');
        $this->mainTitle = '回邮模版';
        $this->viewPath = 'logistics.emailTemplate.';
    }

    /**
     * 存储
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $arr = request()->all();
        foreach($arr as $key => $value) {
        	if(empty($value)) {
        		unset($arr[$key]);
        	}
        }
        //var_dump($arr);exit;
        $model = $this->model->create($arr);
        $this->eventLog(\App\Models\UserModel::find(request()->user()->id)->name, '数据新增', json_encode($model));
        return redirect($this->mainIndex);
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