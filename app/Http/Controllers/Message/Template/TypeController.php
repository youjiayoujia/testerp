<?php
/**
 * 信息模版类型控制器
 *
 * 2016-01-14
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Message\Template;

use App\Http\Controllers\Controller;
use App\Models\Message\Template\TypeModel;

class TypeController extends Controller
{
    public function __construct(TypeModel $type)
    {
        $this->model = $type;
        $this->mainIndex = route('messageTemplateType.index');
        $this->mainTitle = '信息模版类型';
        $this->viewPath = 'message.template.type.';
    }

    //展示
    public function show($id)
    {
        $type = $this->model->find($id);

        if (!$type) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'parents' => TypeModel::where('parent_id', 0)->get(),
            'model' => $type,
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
            'parents' => TypeModel::where('parent_id', 0)->get(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $type = $this->model->find($id);
        if (!$type) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'parents' => TypeModel::where('parent_id', 0)->get(),
            'model' => $type,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * AJAX 获取信息模版类型下的子类型
     *
     * @return string
     */
    public function ajaxGetChildren()
    {
        if (request()->ajax()) {
            $type = $this->model->find(request()->input('id'));
            if ($type) {
                $response = $type->children()->get()->toJson();
                return $response;
            }
        }

        return 'error';
    }

    /**
     * AJAX 获取信息模版类型下的所有模版
     *
     * @return string
     */
    public function ajaxGetTemplates()
    {
        if (request()->ajax()) {
            $type = $this->model->find(request()->input('id'));
            if ($type) {
                $response = $type->templates()->get()->toJson();
                return $response;
            }
        }

        return 'error';
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

        if($model->parent_id == 0){//一级分类
            $type_sec = $this->model->where('parent_id',$model->id)->get();
            if(!$type_sec->isEmpty()){
                foreach ($type_sec as $sec){
                    $templates = $sec->templates()->get();
                    if(!$templates->isEmpty()){
                        foreach ($templates as $template){
                            $template->delete();
                        }
                    }
                    $sec->delete();
                }

            }
            $model->destroy($id);

        }else{
            $templates = $model->templates()->get();
            if(!$templates->isEmpty()){
                foreach ($templates as $template){
                    $template->delete();
                }
            }
            $model->delete($id);
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '操作成功.') );
    }

}