<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\channel\ebayProductModel;
use App\Models\Product\channel\aliexpressProductModel;
use App\Models\Product\channel\b2cProductModel;
use App\Models\Product\SupplierModel;
use App\Http\Controllers\Controller;

class SelectProductController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier)
    {
        $this->mainIndex = route('product.index');
        $this->channelProduct = $amazonProductModel;
        $this->product = $productModel;
        $this->supplier = $supplier;
        $this->mainTitle = '选款选中';
        $this->viewPath = 'product.selectProduct.';
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
            'data' => $this->autoList($this->product->where('status','>=','0')),
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
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->product->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $data['edit_status'] = 'canceled';
        $model->update($data);
        $model->destroy($id);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '删除成功.'));
    }

    /**
     * 产品选中
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function beChosed()
    {
        $channel_ids = request()->input('channel_ids');
        $channel_ids_arr = explode(',', $channel_ids);
        $product_id_str = request()->input('product_ids');
        $product_id_arr = explode(',',$product_id_str);
        //创建亚马逊product
        foreach($product_id_arr as $product_id){
            $productModel = $this->product->find($product_id);
            //ERP中如果该产品之前没有创建item,就创建item
            $data = [];
            //如果该渠道之前没有被选中过,创建该渠道下的product
            foreach($channel_ids_arr as $channel_id){
                switch ($channel_id) {
                    case '1':
                        $model = new amazonProductModel();
                        if(count($productModel->amazonProduct)==0){           
                            $model->createAmazonProduct($productModel->toArray());
                        }
                        break;

                    case '2':
                        $model = new ebayProductModel();
                        if(count($productModel->ebayProduct)==0){           
                            $model->createEbayProduct($productModel->toArray());
                        }
                        break;

                    case '3':
                        $model = new aliexpressProductModel();
                        if(count($productModel->aliexpressProduct)==0){           
                            $model->createaliexpressProduct($productModel->toArray());
                        }
                        break;

                    case '4':
                        $model = new b2cProductModel();
                        if(count($productModel->b2cProduct)==0){           
                            $model->createb2cProduct($productModel->toArray());
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }                
            }
            
            $data['edit_status'] = "picked";
            $productModel->update($data);
        }
        

        return 1;
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

     
}
