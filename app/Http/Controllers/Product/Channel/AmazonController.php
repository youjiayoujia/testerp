<?php

namespace App\Http\Controllers\Product\Channel;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\SupplierModel;
use App\Http\Controllers\Controller;

class AmazonController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier)
    {
        $this->mainIndex = route('amazonProduct.index');
        $this->model = $amazonProductModel;
        $this->product = $productModel;
        $this->supplier = $supplier;
        $this->mainTitle = '亚马逊选中产品';
        $this->viewPath = 'product.channel.amazon.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_ids = request()->input('product_ids');
        $channel_id = request()->input('channel_id');
        if($product_ids){

        }
        $response = [
            'metas' => $this->metas('index'),
            'data' => $this->autoList($this->model),
        ];

        return view( $this->viewPath .'index', $response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->model->find($id),
            'suppliers' =>$this->supplier->all(),
        ];

        return view($this->viewPath . 'edit', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        request()->flash();
        //$this->validate(request(), $this->model->rules('update',$id));
        $editStatus = request()->input('edit');
        $data = request()->all();
        $data['status'] = $editStatus;
        $amazonProductModel = $this->model->find($id);
        $amazonProductModel->updateAmazonProduct($data);
        return redirect($this->mainIndex);
    }


    /**
     * 产品图片编辑界面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function amazonProductEditImage()
    {
        $id = request()->input('id');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->model->find($id),
        ];

        return view($this->viewPath . 'editImage', $response);
    }

    /**
     * 产品图片编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function amazonProductUpdateImage()
    {
        $id = request()->input('id');
        request()->flash();
        $amazonProductModel = $this->model->find($id);
        $amazonProductModel->updateAmazonProductImage(request()->all(),request()->files);

        return redirect($this->mainIndex);
    }

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examineAmazonProduct()
    {
        $id = request()->input('product_ids');
        $status = request()->input('status');
        $amazonProductModel = $this->model->find($id);
        $amazonProductModel->examineAmazonProduct($status);

        return 1;
    }
     
}
