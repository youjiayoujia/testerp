<?php
/**
 * 回复队列控制器
 *
 * 2016-02-01
 * @author: Vincent<nyewon@gmail.com>
 */

namespace App\Http\Controllers\Message;

use App\Models\UserModel;
use App\Http\Controllers\Controller;
use App\Models\Message\SendemailModel;
use App\Models\Message\Template\TypeModel;

class SendemailController extends Controller
{
    public function __construct(SendemailModel $reply)
    {
        $this->model = $reply;
        $this->mainIndex = route('sendemail.index');
        $this->mainTitle = '直接发邮件';
        $this->viewPath = 'message.sendemail.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'parents' => TypeModel::where('parent_id', 0)->get(),
            'users' => UserModel::all(),
        ];
        return view($this->viewPath . 'create', $response);
    }

    //保存直接发送邮件到库
    public function save()
    {
        $data=array();
        if(request()->input('to_email')){
            $data['to']= "service@choies.com";
            $data['to_email']=request()->input('to_email');
            $data['title']=request()->input('title');
            $data['content']=request()->input('content');
            $this->model->create($data);
            return redirect($this->mainIndex)->with('alert', $this->alert('success', '保存成功!'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', '邮箱不能为空!'));
        }
    }


}