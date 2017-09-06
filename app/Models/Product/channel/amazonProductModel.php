<?php

namespace App\Models\Product\channel;

use App\Models\Product\ImageModel;
use App\Base\BaseModel;

class amazonProductModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amazon_products';

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
     * 渠道产品与ERP产品一对一关系
     * 2016-3-11 14:00:41 YJ
     */
    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel','product_id');
    }

    /**
     * 创建渠道产品
     * 2016-3-11 14:00:41 YJ
     * @param array $data
     */
    public function createAmazonProduct($data)
    {   
        $data['product_id'] = $data['id'];
        $data['status'] = 0;
        
        $this->create($data);
    }

    /**
     * 编辑渠道产品资料
     * 2016-3-11 14:00:41 YJ
     * @param array $data
     */
    public function updateAmazonProduct($data)
    {   
        $this->update($data);
    }

    /**
     * 编辑渠道产品图片资料
     * 2016-3-11 14:00:41 YJ
     * @param array $data ,$files 图片
     */
    public function updateAmazonProductImage($data,$files = null)
    {   
        $imageModel = new ImageModel();
        $imageModel->imageCreate($data,$files);
        $data['status'] = 2;
        $this->update($data);
    }

    /**
     * 渠道产品审核
     * 2016-3-11 14:00:41 YJ
     * @param int $status 审核状态
     */
    public function examineAmazonProduct($status)
    {   
        $data['status'] = $status;
        $this->update($data);
    }

}
