<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-13
 * Time: 11:29
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
class EbayAccountSetModel extends BaseModel
{
    protected $table = 'ebay_account_set';
    protected $fillable = [
        'account_id',
        'big_paypal',
        'small_paypal',
        'currency',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => []
    ];


    public function getMixedSearchAttribute()
    {
        $ebayProduct = new EbayPublishProductModel();
        return [
            'filterSelects' => [
                'account_id' => $ebayProduct->getChannelAccount(),
            ]
        ];
    }
    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function bigPaypal()
    {
        return $this->belongsTo('App\Models\PaypalsModel', 'big_paypal', 'id');
    }

    public function smallPaypal()
    {
        return $this->belongsTo('App\Models\PaypalsModel', 'small_paypal', 'id');
    }

    /** 根据账号 金额 币种 获取对应的大小PP
     * @param $price
     * @param $account_id
     * @param $currency
     * @return bool
     */
    public function getPayPalByPrice($price,$account_id,$currency){
        $result = $this->where('account_id',$account_id)->first();
        if($result){
            $currency_info = json_decode($result->currency,true);
            if(isset($currency_info[$currency])){
                if($price>$currency_info[$currency]){
                    return $result->bigPaypal->paypal_email_address;
                }else{
                    return $result->smallPaypal->paypal_email_address;
                }

            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}