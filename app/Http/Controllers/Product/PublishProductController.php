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

class PublishProductController extends Controller
{

    public function __construct(amazonProductModel $amazonProductModel,ProductModel $productModel,SupplierModel $supplier,CurrencyModel $currencyModel,ImageModel $imageModel)
    {
        $this->mainIndex = route('EditProduct.index');
        $this->channelProduct = $amazonProductModel;
        $this->product = $productModel;
        $this->image = $imageModel;
        $this->currency = $currencyModel;
        $this->supplier = $supplier;
        $this->mainTitle = '选款发布';
        $this->viewPath = 'product.publishProduct.';
    }

    /**
     * Display a listing of the resource.
     * //todo:过滤条件的问题
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'metas' => $this->metas('index'),
            'data' => $this->autoList($this->product, $this->product->where('examine_status','pass')),
        ];

        return view( $this->viewPath .'index', $response);

    }

}