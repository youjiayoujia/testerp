<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-23
 * Time: 17:18
 */

namespace App\Http\Controllers\Publish\Wish;

use App\Http\Controllers\Controller;
use App\Models\Publish\Wish\WishSellerCodeModel;
use App\Models\UserModel;

class WishSellerCodeController extends Controller
{
    public function __construct(WishSellerCodeModel $sellerCode)
    {
        $this->model = $sellerCode;
        $this->mainIndex = route('wishSellerCode.index');
        $this->mainTitle = 'wish销售代码';
        $this->viewPath = 'publish.wish.wishSellerCode.';
    }



    public function create()
    {

        $this->model->getAllWishCode();
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