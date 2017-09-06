<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-28
 * Time: 14:49
 */

namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\Publish\Ebay\EbayPublishProductModel;
class EbayTimingSetModel extends BaseModel
{
    protected $table = 'ebay_timing_set';
    protected $fillable = [
        'name',
        'account_id',
        'site',
        'warehouse',
        'start_time',
        'end_time',
    ];

    public $searchFields = ['name'=>'规则名称'];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function getMixedSearchAttribute()
    {
        $ebaySite = new EbaySiteModel();
        $ebayProduct = new EbayPublishProductModel();
        return [
            'filterSelects' => [
                'account_id' => $ebayProduct->getChannelAccount(),
                'site' => $ebaySite->getSite('site_id'),
                'warehouse' => config('ebaysite.warehouse')
            ]
        ];
    }
    public function channelAccount()
    {
        return $this->belongsTo('App\Models\Channel\AccountModel', 'account_id', 'id');
    }

    public function getSiteTime($where){
        $result = $this->where($where)->first();
        if(isset($result->id)){
            if(!isset(config('ebaysite.site_time')[$result->site])){ //不存在这个站点的差
                return false;
            }
            $start_arr = explode(':',$result->start_time);
            $end_arr = explode(':',$result->end_time);
            $start = $start_arr[0]*60*60+$start_arr[1]*60;
            $end = $end_arr[0]*60*60+$end_arr[1]*60;
            $rand = rand(1,$end- $start);
            $today = date('Y-m-d',time());
            $start_time = $today.' '.$result->start_time;
            $mid  = config('ebaysite.site_time')[$result->site];
            $site_time_cn = date('Y-m-d H:i:s',strtotime($start_time)-$mid*60*60);
            if(strtotime($site_time_cn)-time()>0){
                $last_start_time =$site_time_cn;
            }else{
                $last_start_time =  date('Y-m-d H:i:s',strtotime($start_time)+24*60*60);
            }
            $last_time =  strtotime($last_start_time)+$rand - time();
            return $last_time;


        }else{
            return false;
        }
    }
}