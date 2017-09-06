<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-28
 * Time: 10:51
 */

namespace App\Http\Controllers\Publish\Ebay;

use App\Http\Controllers\Controller;
use App\Models\Publish\Ebay\EbaySellerCodeModel;
use App\Models\UserModel;

class EbaySellerCodeController extends Controller
{
    public function __construct(EbaySellerCodeModel $sellerCode)
    {
        $this->model = $sellerCode;
        $this->mainIndex = route('ebaySellerCode.index');
        $this->mainTitle = 'Ebay销售代码';
        $this->viewPath = 'publish.ebay.ebaySellerCode.';
    }

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

    public function create()
    {

        $this->model->getAllEbayCode();
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

    //edit
}