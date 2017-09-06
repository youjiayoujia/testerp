<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-08-19
 * Time: 13:31
 */

namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
class EbayPublishProductDetailModel extends BaseModel
{

    protected $table = 'ebay_publish_product_detail';

    protected $fillable = [
        'publish_id',
        'product_id',
        'sku',
        'start_price',
        'quantity',
        'erp_sku',
        'quantity_sold',
        'item_id',
        'seller_id',
        'status',
        'start_time',
        'update_time',
    ];

    public $searchFields =  ['item_id' => 'Ebay ItemID'];
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
               // 'details'=>['erp_sku']
            ],
            'sectionSelect' => [
                //'price' => ['number_sold'],
               // 'time' =>['publishedTime']
            ],
            'selectRelatedSearchs' => [
            ]
        ];
    }

    public function ebayProduct()
    {
        return $this->belongsTo('App\Models\Publish\Ebay\EbayPublishProductModel', 'publish_id');
    }
    public function erpProduct()
    {
        return $this->belongsTo('App\Models\ItemModel', 'product_id');
    }

    public function getEbayStatusAttribute()
    {
        return $this->status==1 ? '是' : '否';
    }


    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'seller_id', 'id');
    }

}