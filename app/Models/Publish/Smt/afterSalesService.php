<?php

namespace App\Models\Publish\Smt;

use App\Base\BaseModel;
use App\Models\ChannelModel;

class afterSalesService extends BaseModel
{
    protected $table = "after_sales_service";
    protected $fillable = ['plat','token_id','name','content'];
    
    public $searchFields = ['name'=>'模版名称'];
    
    public function account(){
        return $this->belongsTo('App\Models\Channel\AccountModel', 'token_id');
    }
    
    public function getMixedSearchAttribute()
    {
        return [        
            'filterSelects' => [
                'token_id' => $this->getAccountNumber('App\Models\Channel\AccountModel','alias'),
            ],          
        ];
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
}
