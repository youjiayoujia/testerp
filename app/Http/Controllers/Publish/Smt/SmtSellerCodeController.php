<?php

namespace App\Http\Controllers\Publish\Smt;

use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtUserSaleCode;

class SmtSellerCodeController extends Controller
{
    public function __construct(smtUserSaleCode $sellerCode)
    {
        $this->model = $sellerCode;
        $this->mainIndex = route('smtSellerCode.index');
        $this->mainTitle = 'smt销售代码';
        $this->viewPath = 'publish.smt.smtSellerCode.';
    }


    public function create()
    {
        $this->model->getAllSmtCode();
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'users' => UserModel::orderBy('name', 'asc')->get(['id', 'name'])
        ];
        return view($this->viewPath . 'create', $response);
    }


    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'users' => UserModel::orderBy('name', 'asc')->get(['id', 'name'])

        ];
        return view($this->viewPath . 'edit', $response);
    }


}
