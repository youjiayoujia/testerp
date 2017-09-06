<?php

namespace App\Http\Controllers;

use App\Models\MailPushModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tool;

class MailPushController extends Controller
{
    public function __construct(MailPushModel $model)
    {
        $this->model = $model;
        $this->mainIndex = route('mail_push.index');
        $this->mainTitle = '邮件推送';
        $this->viewPath = 'system.mail_push.';
    }

//'refer_url' => Tool::referUrl(),


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
            'refer_url' => Tool::referUrl(),
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
        return redirect(Tool::referUrl($this->mainIndex))->with('alert', $this->alert('success', '操作成功.'));
    }
}
