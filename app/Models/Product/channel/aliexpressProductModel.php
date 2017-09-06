<?php

namespace App\Models\Product\channel;

use App\Models\Product\ImageModel;
use App\Base\BaseModel;

class aliexpressProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'aliexpress_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','choies_info','name','c_name','supplier_id','supplier_sku','product_sale_url','purchase_sale_url',
                            'purchase_price','purchase_carriage','description','weight','supplier_info','remark','image_remark','status'];

    public $rules = [
        'create' => [
            'name' => 'required',
            'c_name' => 'required',
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',
        ],
        'update' => [
            'name' => 'required',
            'c_name' => 'required',   
            'purchase_price' => 'required|numeric',
            'purchase_carriage' => 'required|numeric',    
        ]
    ];

    /**
     * 创建ebay渠道产品
     * 2016-3-11 14:00:41 YJ
     * @param array $data
     */
    public function createAliexpressProduct($data)
    {   
        $data['product_id'] = $data['id'];
        $data['status'] = 0;
        
        $this->create($data);
    }

}
