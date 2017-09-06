<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Purchase\AlibabaSupliersAccountModel;
use App\Models\UserModel;
use Tool;


class AccountController extends Controller
{


    public function __construct(AlibabaSupliersAccountModel $account)
    {
        $this->model = $account;
        $this->mainIndex = route('purchaseAccount.index');
        $this->mainTitle = '阿里巴巴采购账号';
        $this->viewPath = 'purchase.accounts.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model, null, ['*'], null, null, ['user']),

        ];
        return view($this->viewPath . 'index',$response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( )
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $account = $this->model->find($id);
        if (!$account) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas'   => $this->metas(__FUNCTION__),
            'model'   => $account,
            'users' => UserModel::where('is_available','=',1)->get(),
            'refer_url' => Tool::referUrl(),

        ];
        return view($this->viewPath . 'edit', $response);
    }

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
/*    public function update($id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
