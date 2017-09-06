<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-15
 * Time: 15:14
 */
namespace App\Models\Publish\Wish;
use App\Base\BaseModel;
class WishPublishProductDetailModel extends BaseModel
{
    protected $table = 'wish_publish_product_detail';
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
    protected $searchFields = [];
    protected $rules = [];
    public function ebayPublishProduct()
    {
        return $this->belongsTo('App\Models\Publish\Wish\WishPublishProductModel','product_id');
    }
    public function getMixedSearchAttribute()
    {
        return [
            'filterSelects' => [
            ],
            'filterFields' => [
                'productID',
            ],
            'relatedSearchFields' => [
                'details'=>['erp_sku']
            ],
            'sectionSelect' => [
                'price' => ['number_sold'],
                'time' =>['publishedTime']
            ],
            'selectRelatedSearchs' => [
            ]
        ];
    }
    public function detail()
    {
        return $this->belongsTo('App\Models\Publish\Wish\WishPublishProductModel','product_id');
    }
    public function belongs_product()
    {
        return $this->belongsTo('App\Models\Publish\Wish\WishPublishProductModel', 'product_id', 'id');
    }
    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }
    public function details()
    {
        return $this->belongsTo('App\Models\ProductModel', 'erp_sku', 'model');
    }
}