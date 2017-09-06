<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/9/14
 * Time: 15:31
 */
namespace App\Models\Message\Issues;
use App\Base\BaseModel;
use App\Models\Channel\AccountModel;
use Carbon\Carbon;

class AliexpressIssueListModel extends BaseModel
{
    protected $table = 'aliexpress_issues_list';
    public $rules = [];
    public $searchFields =[];
    protected $guarded = [];

    public function account()
    {
        return $this->hasOne('App\Models\Channel\AccountModel','id','account_id');

    }

    public function detail()
    {
        return $this->hasOne('App\Models\Message\Issues\AliexpressIssuesDetailModel', 'id', 'issue_list_id');
    }

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'aliexpress_issues_list.orderId',
            ],
           'filterSelects' => [
               'issueType' => config('message.aliexpress.issueType'),
                'reasonChinese' => $this->distinct()->get(['reasonChinese'])->pluck('reasonChinese', 'reasonChinese'),
            ],
           'selectRelatedSearchs' => [
               'account' => ['account' => AccountModel::all()->pluck('alias', 'account')],
            ],
        ];
    }

    public function getIssueTypeNameAttribute(){
        if($this->issueType){
            return config('message.aliexpress.issueType')[$this->issueType];
        }else{
            return '';
        }
    }
    public function getaccountNameAttribute(){
        if($account = $this->account){
            return  $account->alias ? $account->alias : '';
        }else{
            return '';
        }
    }

    public function getIsPlatformProcessNameAttribute(){
        $name = '平台未处理';
        if($this->is_platform_process != 0){
            $name = '平台已处理';
        }
        return $name;
    }

    public function platformDeal($idsAry=null){
        $result = false;
        if(is_array($idsAry)){
            $result = $this->whereIn('id', $idsAry)
                           ->update(['is_platform_process' => 1]);
        }
        return ! empty($result) ? $result : false;
    }

    public function getTimeLimitAttribute()
    {
         $dt = Carbon::parse($this->gmtCreate);
         $start = $dt->timestamp;
         $now = Carbon::now()->timestamp;
         $end = $dt->timestamp + 5*60*60*24;
         $over = $end - $now;
         return $dt->timestamp($over)->toTimeString();


    }
}
