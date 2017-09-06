<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductModel;
use App\Models\Product\channel\amazonProductModel;
use App\Models\Product\channel\ebayProductModel;
use App\Models\Product\channel\aliexpressProductModel;
use App\Models\Product\channel\b2cProductModel;
use App\Models\Product\ProductEnglishValueModel;
use App\Models\Product\SupplierModel;
use App\Models\CurrencyModel;
use App\Models\Product\ImageModel;
use App\Http\Controllers\Controller;
use Gate;
use App\User;
//use App\Post;

class EditProductController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier,CurrencyModel $currencyModel,ImageModel $imageModel)
    {
        $this->mainIndex = route('product.index');
        $this->channelProduct = $amazonProductModel;
        $this->product = $productModel;
        $this->image = $imageModel;
        $this->currency = $currencyModel;
        $this->supplier = $supplier;
        $this->mainTitle = '选款产品编辑';
        $this->viewPath = 'product.editProduct.';
    }

    /**
     * Display a listing of the resource.
     * //todo:过滤条件的问题
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('product_admin','product|show')) {
            echo "没有权限";exit;
        }
        $response = [
            'metas' => $this->metas('index'),
            'data' => $this->autoList($this->product, $this->product->where('edit_status','!=','canceled')->where('edit_status','!=','')),
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
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->product->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'suppliers' =>$this->supplier->all(),
        ];
        return view($this->viewPath . 'show', $response);
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
        $model = $this->product->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();

        $editStatus = request()->input('edit');
        $data = request()->all();  
        $productModel = $this->product->find($id);    
        //更新英文信息
        $ProductEnglishValueModel = new ProductEnglishValueModel();
        $data['product_id'] = $productModel->id;
        $english = $ProductEnglishValueModel->firstOrNew(['product_id'=>$id]);
        //如果没保存过对应产品ID的英文资料,create，否则就更新
        if(count($english->toArray())==1){
            $english->create($data);
        }else{
            $english->update($data);
        }
        //去除英文产品表和产品表重名字段以更新
        unset($data['description']);
        $data['edit_user'] = empty(request()->user()) ? 0 : request()->user()->id;
        $productModel->update($data);
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '资料编辑成功.'));
    }

    /**
     * 产品图片编辑界面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productEditImage()
    {
        $id = request()->input('id');
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $this->product->find($id),
        ];

        return view($this->viewPath . 'editImage', $response);
    }

    /**
     * 产品图片编辑
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productUpdateImage()
    {
        $id = request()->input('id');
        request()->flash();
        $data = request()->all();
        $ProductModel = $this->product->find($id);
        if($data['uploadType']=='image'){
            $ProductModel->updateProductImage($data,request()->files);
        }else{
            $this->image->imageCreate(request()->all(), request()->files);
        }
        $user = [];
        $user['edit_image_user'] = empty(request()->user()) ? 0 : request()->user()->id;
        $ProductModel->update($user);
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '图片编辑成功.'));
    }

    /**
     * 产品审核
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examineProduct()
    {
        $id = request()->input('product_id');
        $status = request()->input('status');
        $productModel = $this->product->find($id);
        $productModel->examineProduct($status);

        return 1;
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function price()
    {
        $rate = $this->currency->getRate('USD');
        $type = request()->input('type');
        $price = (float)request()->input('price');
        $weight = (float)request()->input('weight');
        //价格系数
        $price_coe = 1.4;
        if($price<30){
            $price_coe = 2.2;
        }elseif($price<50){
            $price_coe = 1.98;
        }
        elseif($price<100){
            $price_coe = 1.97;
        }
        elseif($price<150){
            $price_coe = 1.95;
        }
        elseif($price<200){
            $price_coe = 1.9;
        }
        elseif($price<250){
            $price_coe = 1.7;
        }
        elseif($price<300){
            $price_coe = 1.7;
        }
        elseif($price<350){
            $price_coe = 1.6;
        }
        elseif($price<400){
            $price_coe = 1.5;
        }
        
        //重量系数
        $weight_coe = 1.3;
        if($weight < 0.5){
            $weight_coe = 2.6;
        }elseif($weight < 1){
            $weight_coe = 2.5;
        }
        elseif($weight < 1.5){
            $weight_coe = 1.8;              
        }
        elseif($weight < 2){
            $weight_coe = 1.6;               
        }
        $result = [];
        //运费
        $ship_price = request()->input('ship_price');
        //销售价美元
        $real_price = request()->input('real_price');
        $price_temp = round(($price * $price_coe + $weight * $weight_coe * 110 + 10) / $rate, 2);
        //计算出来的销售价美元
        $sale_price = intval($price_temp)+0.99;
        //利润
        $profit = round(($sale_price * $rate - $price - $weight * 120 - $ship_price) / ($sale_price * $rate), 4);
        
        $result['sale_price'] = $sale_price;
        $result['price'] = $price;
        $result['price_coe'] = $price_coe;
        $result['weight'] = $weight;
        $result['weight_coe'] = $weight_coe;
        $result['profit'] = $profit;

        if($real_price>0){
            $r_profit = round(($real_price * $rate - $price - $weight * 120 - $ship_price) / ($real_price * $rate), 4);
            $result['r_price'] = $real_price;
            $result['r_profit'] = $r_profit;
        }else{
            $result['r_price'] = $sale_price;
            $result['r_profit'] = $profit;
        }
        if($type=='cost'){
            $cost = request()->input('cost');
            $p_cost = round(($cost + $ship_price + $weight * 110 + 10) / $rate, 2);
            $result['p_cost'] = $p_cost;
            $result['cost'] = $cost;
            $result['ship_price'] = $ship_price;
        }

        return $result;
    }
}
