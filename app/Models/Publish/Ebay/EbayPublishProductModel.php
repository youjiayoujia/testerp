<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-08-19
 * Time: 13:31
 */

namespace App\Models\Publish\Ebay;
use Channel;
use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
use App\Models\ChannelModel;
use App\Models\Publish\Ebay\EbayPublishProductDetailModel;
use App\Models\Publish\Ebay\EbayDescriptionTemplateModel;
class EbayPublishProductModel extends BaseModel
{

    protected $table = 'ebay_publish_product';

    protected $fillable = [
        'account_id',
        'item_id',
        'primary_category',
        'secondary_category',
        'title',
        'sub_title',
        'sku',
        'site_name',
        'site',
        'start_price',
        'quantity',
        'reserve_price',
        'buy_it_now_price',
        'listing_type',
        'view_item_url',
        'listing_duration',
        'dispatch_time_max',
        'private_listing',
        'payment_methods',
        'paypal_email_address',
        'currency',
        'location',
        'postal_code',
        'country',
        'quantity_sold',
        'store_category_id',
        'condition_id',
        'condition_description',
        'picture_details',
        'item_specifics',
        'variation_picture',
        'return_policy',
        'variation_specifics',
        'shipping_details',
        'buyer_requirement',
        'status',
        'is_out_control',
        'multi_attribute',
        'seller_id',
        'description',
        'description_id',
        'description_picture',
        'note',
        'warehouse',
        'start_time',
        'update_time'
    ];

    public $searchFields = ['item_id' => 'Ebay ItemID','id'=>'列表ID'];

    protected $rules = [];


    public function details()
    {
        return $this->hasMany('App\Models\Publish\Ebay\EbayPublishProductDetailModel', 'publish_id', 'id');

    }

    public function getEbayOutControlAttribute()
    {
        return $this->is_out_control==1 ? '是' : '否';
    }

    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo('App\Models\UserModel', 'seller_id', 'id');
    }


    /** 获取对应渠道账号
     * @param $channel_id 渠道号
     * @return array
     */
    public function getChannelAccount(){
        $return=[];
        $channel = ChannelModel::where('name', 'Ebay')->first();
       // $result =  AccountModel::where(['channel_id'=>$channel->id,'is_available'=>'1'])->get();
        $result =  AccountModel::where(['channel_id'=>$channel->id])->get();
        foreach($result as $account){
            $return[$account->id]=$account->alias;
        }
        return $return;
    }


    public function publish($id,$api,$auto=false){
        $data = $this->where('id',$id)->first()->toArray();
        $descriptionTemplate = new EbayDescriptionTemplateModel();
        $data['description'] = $descriptionTemplate->getLastDescription($data['description_id'],json_decode($data['description_picture'],true),$data['title'],$data['description']);
        $data['sku_detail'] = EbayPublishProductDetailModel::where('publish_id',$id)->get()->toArray();
        if($auto){
            if($data['status'] !=1){
                return [
                    'is_success'=>false,
                    'info' =>'该广告不处于待发布状态',
                    'info_all'=>'',
                    'data'=>$data,
                ];
            }
        }

        if($api=='Verify'&&$data['multi_attribute']==0){
            $last_api = 'VerifyAddItem';
        }elseif($api=='Verify'&&$data['multi_attribute']==1){
            $last_api = 'VerifyAddFixedPriceItem';
        }elseif($api=='Add'&&$data['multi_attribute']==0){
            $last_api = 'AddItem';
        }elseif($api=='Add'&&$data['multi_attribute']==1){
            $last_api = 'AddFixedPriceItem';
        }else{
            return [
                'is_success'=>false,
                'info' =>'系统参数错误',
                'info_all'=>'',
                'data'=>$data
            ];
        }

        $account = AccountModel::find($data['account_id']);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $result = $channel->publish($last_api,$data,$data['site']);
        $result['data'] = $data;
        return $result;
    }
}