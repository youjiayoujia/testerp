<?php
/*
 *Time:2016-10-4
 * joom在线数量监控广告详情model
 * user:hejiancheng
 */
namespace App\Models\Publish\Joom;

use App\Base\BaseModel;

class JoomPublishProductDetailModel extends BaseModel
{

    protected $table = 'joom_publish_product_detail';

    protected $fillable = [
        'product_id',
        'account_id',
        'sku',
        'erp_sku',
        'sellerID',
        'price',
        'inventory',
        'color',
        'size',
        'shipping',
        'msrp',
        'shipping_time',
        'main_image',
        'enabled',
        'productID',
        'product_sku_id'
    ];

    public $searchFields = ['erp_sku' => 'erp_sku', 'sku' => 'SKU'];
    protected $rules = [];

    public function getMixedSearchAttribute()
    {
        return [
            'filterSelects' => [
            ],
            'filterFields' => [
                'item_id',
            ],
            'relatedSearchFields' => [

            ],
            'sectionSelect' => [

            ],
            'selectRelatedSearchs' => [
            ]
        ];
    }

    public function product()
    {
        return $this->belongsTo('App\Models\ProductModel', 'erp_sku', 'model');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'erp_sku', 'sku');
    }

}