<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\channel\ebayProductModel;
use App\Models\Product\channel\aliexpressProductModel;
use App\Models\Product\channel\b2cProductModel;
use App\Models\Product\SupplierModel;
use App\Http\Controllers\Controller;

class ExamineProductController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier)
    {
        $this->mainIndex = route('product.index');
        $this->channelProduct = $amazonProductModel;
        $this->product = $productModel;
        $this->supplier = $supplier;
        $this->mainTitle = '选款审核';
        $this->viewPath = 'product.examineProduct.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'metas' => $this->metas('index'),
            'data' => $this->autoList($this->product, $this->product->whereIn('edit_status',array('image_unedited','image_edited'))),
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
            'model' => $this->product->find($id),
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
        $productModel = $this->product->find($id);
        $data = request()->input();
        if($data['examine_status']=='revocation'){
            $data['revocation_user'] = empty(request()->user()) ? 0 : request()->user()->id;
        }else{
            $data['examine_user'] = empty(request()->user()) ? 0 : request()->user()->id;
        }
        $productModel->update($data);
        //ERP中如果该产品之前没有创建item,并且是审核,就创建item
        if($data['examine_status']=='pass'&&empty($productModel->item->toArray())){
            $productModel->createItem();
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '审核状态已更新.'));
        
        
    }

    /**
     * 批量审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examineAll()
    {
        $examine_status = request()->input('examine_status');
        $product_ids = request()->input('product_ids');
        $product_id_arr = explode(',', $product_ids);
        foreach ($product_id_arr as $id) {
            $productModel = $this->product->find($id);
            $data['examine_status'] = $examine_status;
            $productModel->update($data);
            if($data['examine_status']=='pass'&&empty($productModel->item->toArray())){
                $productModel->createItem();
            }
        }

        return 1;
    }
    
     
}
