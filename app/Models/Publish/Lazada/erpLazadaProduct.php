<?php

namespace App\Models\Publish\Lazada;

use App\Base\BaseModel;
use App\Models\ChannelModel;

class erpLazadaProduct extends BaseModel
{
    protected $table = "erp_lazada_products";
    
    protected $fillable = [
        'sellerSku',
        'shopSku',
        'sku',
        'name',
        'variation',
        'quantity',
        'price',
        'salePrice',
        'saleStartDate',
        'saleEndDate',
        'status',
        'productId',
        'account'
    ];
    
    public $searchFields = ['sellerSku' => 'sellerSku','sku' => 'SKU'];
    
    public function getMixedSearchAttribute(){
        return [
            'filterSelects' => [
                'account' => $this->getAccountNumber('App\Models\Channel\AccountModel','account'),
                'status' => config('lazadaProduct.status'),                
            ],
            'selectRelatedSearchs' => [
                'product' => ['status' => config('smt_product.status')],
            ],
        ];
    }
    
    public function product(){
        return $this->belongsTo('App\Models\ProductModel', 'sku','model');
    }
    
    public function item(){
        return $this->belongsTo('App\Models\ItemModel', 'sku','sku');
    }
    
    public function getAccountNumber($model, $name)
    {
        $channel =  ChannelModel::where('driver','lazada')->first();
        $arr = [];
        $inner_models = $model::where('channel_id',$channel->id)->get();
        foreach ($inner_models as $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }
}
