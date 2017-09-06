<?php
/**
 * 海外仓箱子Controller
 *
 * 2016-01-04
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\BoxModel;
use App\Models\LogisticsModel;

class BoxController extends Controller
{
    public function __construct(BoxModel $box)
    {
        $this->model = $box;
        $this->mainIndex = route('box.index');
        $this->mainTitle = '海外仓箱子信息';
        $this->viewPath = 'oversea.box.';
    }

    public function boxSub()
    {
        $id = request('boxId');
        $model = $this->model->find($id);
        if (!$model) {
            return json_encode(false);
        }
        $weight = request('weight');
        $volumn = request('volumn');
        $arr = explode('*', $volumn);
        $model->update(['length' => $arr[0], 'width' => $arr[1], 'height' => $arr[2], 'weight' => $weight]);
        
        return json_encode(true);
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
            'forms' => $model->forms
        ];
        return view($this->viewPath . 'show', $response);
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
            'logisticses' => LogisticsModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);
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
        return redirect($this->mainIndex);
    }

}