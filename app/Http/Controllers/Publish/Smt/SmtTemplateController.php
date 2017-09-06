<?php
namespace App\Http\Controllers\Publish\Smt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtTemplates;


class SmtTemplateController extends Controller
{
    public function __construct(){
        $this->model = new smtTemplates();
        $this->mainTitle = '模版';
        $this->viewPath = 'publish.smt.smtTemplate.';
        $this->mainIndex = route('smtTemplate.index');
    }

    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'index', $response);
    }
    
    public function edit($id){
        $model = $this->model->where('id',$id)->first();    
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' =>$model,
        ];
        return view($this->viewPath . 'edit', $response);
    }
    
    public function store(){
        $data = array();
        $data['id'] = request()->input('id');
        $data['plat'] = request()->input('plat');
        $data['token_id'] = 0;
        $data['name'] = request()->input('name');
        $data['content'] = htmlspecialchars(request()->input('content')); 
        if(isset($data['id']) && $data['id']){
            $this->model->where('id',$data['id'])->update($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '编辑成功!'));
        }else {
            $this->model->create($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '新增成功!'));
        }
    }
    
    public function copyTemplate(){
        $id = request()->input('id');
        $template_info = $this->model->where('id',$id)->first();
        if (!$template_info) {
            $this->ajax_return('ID为' . $id . '的数据不存在，请刷新');
        }
        $template_info = $template_info->toArray();
        $template_info['name'] .= '-copy';
        unset($template_info['id']);
        if ($this->model->create($template_info)) {
            return array('info'=>'复制成功','status'=>true);           
        } else {
            return array('info'=>'复制失败','status'=>false); 
        }
    }

   
}
