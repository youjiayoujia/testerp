<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;
use App\Models\ChannelModel;
use App\Models\Channel\AccountModel;

class smtProductList extends BaseModel
{
    protected $table = "smt_product_list";
    
    protected $fillable = [       
        'product_url',
        'productId',
        'token_id',
        'user_id',
        'ownerMemberId',
        'ownerMemberSeq',
        'subject',
        'productPrice',
        'productMinPrice',
        'productMaxPrice',
        'productStatusType',
        'gmtCreate',
        'gmtModified',
        'wsOfflineDate',
        'wsDisplay',
        'groupId',
        'categoryId',
        'packageLength',
        'packageWidth',
        'packageHeight',
        'grossWeight',
        'deliveryTime',
        'wsValidNum',
        'multiattribute',
        'synchronizationTime',   
        'isRemove',
        'old_token_id',
        'old_productId',
        'quantitySold1'
    ];
    
    public $searchFields = ['productId'=>'产品ID','subject'=>'标题'];
    
    public $rules = [];
    
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => ['productSku' => ['skuCode']],
            'filterFields' => [],
            'filterSelects' => ['token_id' => $this->getAccountNumber('App\Models\Channel\AccountModel','alias')],
            'selectRelatedSearchs' => [],
            'sectionSelect' => [],
        ];
    }
    
    public function details()
    {
        return $this->hasOne('App\Models\Publish\Smt\smtProductDetail', 'productId', 'productId');
    }
    
    public function accounts(){
        return $this->belongsTo('App\Models\Channel\AccountModel', 'token_id');
    }
    
    public function  productSku(){
        return $this->hasMany('App\Models\Publish\Smt\smtProductSku','productId','productId');
    }
    
    public function userInfo(){
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }
    
    public function getAccountNumber($model, $name)
    {
        $channel =  ChannelModel::where('driver','aliexpress')->first();
        $arr = [];
        $inner_models = $model::where('channel_id',$channel->id)->get();
        foreach ($inner_models as $single) {
            $arr[$single->id] = $single->$name;
        }
        return $arr;
    }
    
    public function getAccountInfoAttribute()
    {
        $channel =  ChannelModel::where('driver','aliexpress')->first();
        $arr = [];
        $inner_models = AccountModel::where('channel_id',$channel->id)->get();
        foreach ($inner_models as $single) {
            $arr[$single->account] = $single->id;
        }
        return $arr;
    }
    
    
}
