<?php
/*
 *Time:2016-10-4
 * joom在线数量监控广告model
 * user:hejiancheng
 */
namespace App\Models\Publish\Joom;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
class JoomPublishProductModel extends BaseModel
{

    protected $table = 'joom_publish_product';

    protected $fillable = [
        'account_id',
        'productID',
        'publishedTime',
        'status',
        'is_promoted',
        'review_status',
        'sellerID',
        'product_description',
        'product_name',
        'parent_sku',
        'tags',
        'product_type_status',
        'brand',
        'landing_page_url',
        'upc',
        'extra_images',
        'number_saves',
        'number_sold'
    ];

    public $searchFields = [];


    protected $rules = [];


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

    public function details()
    {
        return $this->hasMany('App\Models\Publish\Joom\JoomPublishProductDetailModel', 'productID', 'productID');
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'sellerID', 'id');
    }

    /** 获取对应渠道账号
     * @param $channel_id 渠道号
     * @return array
     */
    public function getChannelAccount($channel_id){
        $return=[];
        $result =  AccountModel::where(['channel_id'=>$channel_id,'is_available'=>'1'])->get();
        foreach($result as $account){
            $return[$account->id]=$account->account;
        }
        return $return;
    }

}