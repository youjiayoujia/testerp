<?php
/**
 * 渠道账号模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Channel;

use App\Base\BaseModel;

class AccountModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $searchFields = ['account' => '渠道帐号', 'alias' => '帐号别名'];

    protected $rules = [
        'create' => [
            'account' => 'required|unique:channel_accounts,account',
            'alias' => 'required',
            'channel_id' => 'required',
            'operator_id' => 'required',
            'customer_service_id' => 'required',
            'catalog_rates_channel_id' => 'required',
        ],
        'update' => [
            'account' => 'required|unique:channel_accounts,account,{id}',
            'alias' => 'required',
            'channel_id' => 'required',
            'operator_id' => 'required',
            'customer_service_id' => 'required',
            'catalog_rates_channel_id' => 'required',
        ]
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }
    public function catalogChannel()
    {
        return $this->belongsTo('App\Models\Channel\CatalogRatesModel', 'catalog_rates_channel_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountriesModel', 'country_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'operator_id', 'id');
    }

    public function customer_service()
    {
        return $this->belongsTo('App\Models\UserModel', 'customer_service_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\OrderModel', 'channel_account_id');
    }

    public function paypal()
    {
        return $this->belongsToMany('App\Models\PaypalsModel', 'channel_account_paypal',
            'channel_account_id', 'paypal_id');
    }
    public function AutoReplyRules()
    {
        return $this->hasMany('App\Models\Message\AutoReplyRulesModel', 'channel_id', 'channel_id');
    }

    public function getClearanceAttribute()
    {
        return $this->is_clearance ? '是' : '否';
    }

    public function getAvailableAttribute()
    {
        return $this->is_available ? '是' : '否';
    }

    public function getApiStatusAttribute()
    {
        $status = [];
        switch ($this->channel->driver) {
            case 'amazon':
                $status = ['Shipped', 'Unshipped', 'PartiallyShipped'];
                break;
            case 'aliexpress':
                $status = ['IN_CANCEL','WAIT_SELLER_SEND_GOODS'];
                break;
            case 'lazada':
                $status = ['pending'];
                break;
            case 'wish':
                $status = ['All'];
                break;
            case 'ebay':
                $status = ['All'];
                break;
            case 'cdiscount':
                $status = ['WaitingForShipmentAcceptation'];
                break;
        }
        return $status;
    }

    public function getApiConfigAttribute()
    {
        $config = [];
        switch ($this->channel->driver) {
            case 'amazon':
                $config = [
                    'serviceUrl' => $this->amazon_api_url,
                    'MarketplaceId.Id.1' => $this->amazon_marketplace_id,
                    'SellerId' => $this->amazon_seller_id,
                    'AWSAccessKeyId' => $this->amazon_accesskey_id,
                    'AWS_SECRET_ACCESS_KEY' => $this->amazon_accesskey_secret,
                    'GmailSecret' => $this->message_secret,
                    'GmailToken' => $this->message_token,
                    'account_id' => $this->id,
                    'account_email' => $this->account,
                ];
                break;
            case 'aliexpress':
                $config = [
                    'appkey' => $this->aliexpress_appkey,
                    'appsecret' => $this->aliexpress_appsecret,
                    'returnurl' => $this->aliexpress_returnurl,
                    'access_token_date' => $this->aliexpress_access_token_date,
                    'refresh_token' => $this->aliexpress_refresh_token,
                    'access_token' => $this->aliexpress_access_token,
                    'aliexpress_member_id' => $this->aliexpress_member_id,
                    'operator_id' => $this->operator_id,
                    'customer_service_id' => $this->customer_service_id,
                ];
                break;
            case 'lazada':
                $config = [
                    'lazada_account' => $this->lazada_account,
                    'lazada_access_key' => $this->lazada_access_key,
                    'lazada_user_id' => $this->lazada_user_id,
                    'lazada_site' => $this->lazada_site,
                    'lazada_currency_type' => $this->lazada_currency_type,
                    'lazada_currency_type_cn' => $this->lazada_currency_type_cn,
                    'lazada_api_host' => $this->lazada_api_host,
                ];
                break;
            case 'wish':
                $config = [
                    'publish_code' => $this->wish_publish_code,
                    'client_id' => $this->wish_client_id,
                    'client_secret' => $this->wish_client_secret,
                    'redirect_uri' => $this->wish_redirect_uri,
                    'refresh_token' => $this->wish_refresh_token,
                    'access_token' => $this->wish_access_token,
                    'expiry_time' => $this->wish_expiry_time,
                    'proxy_address' => $this->wish_proxy_address,
                    'sku_resolve' => $this->wish_sku_resolve,
                ];
                break;
            case 'ebay':
                $config = [
                    'requestToken' => $this->ebay_token,
                    'devID'        => $this->ebay_developer_devid,
                    'appID'        => $this->ebay_developer_appid,
                    'certID'       => $this->ebay_developer_certid,
                    'accountName'    => $this->account,
                    'accountID'    => $this->id
                ];
                break;
            case 'cdiscount':
                $config = [
                    'cd_sales_account' => $this->cd_sales_account,
                    'cd_pw' => $this->cd_pw,
                    'cd_token_id' => $this->cd_token_id,
                    'cd_account' => $this->cd_account,
                    'cd_currency_type_cn' => $this->cd_currency_type_cn,
                    'cd_currency_type' => $this->cd_currency_type,
                    'cd_expires_in' => $this->cd_expires_in,
                ];
                break;
			case 'joom':
                $config = [
                    'publish_code' => $this->joom_publish_code,
                    'client_id' => $this->joom_client_id,
                    'client_secret' => $this->joom_client_secret,
                    'redirect_uri' => $this->joom_redirect_uri,
                    'refresh_token' => $this->joom_refresh_token,
                    'access_token' => $this->joom_access_token,
                    'expiry_time' => $this->joom_expiry_time,
                    'proxy_address' => $this->joom_proxy_address,
                    'sku_resolve' => $this->joom_sku_resolve,
                ];
                break;
        }
        return $config;
    }

    public function getcatalogChannelNameAttribute(){
            return !empty($this->catalogChannel) ? $this->catalogChannel->name : '';
    }

    public function replies()
    {
        return $this->hasManyThrough('App\Models\Message\ReplyModel', 'App\Models\Message\MessageModel',
            'account_id', 'message_id');
    }

    public function getAllImageDomain(){
        $image_domain = [];
        $result = $this->where('is_available',1)->where('image_domain','!=','')->get();
        if(!empty($result)){
            foreach($result as $v){
                $image_domain[] = $v['image_domain'];
            }
        }
        return $image_domain;
    }

    public function getAutoReplyRulesOn()
    {
        $this->AutoReplyRules()->where('status', 'ON')->get();
    }
}
