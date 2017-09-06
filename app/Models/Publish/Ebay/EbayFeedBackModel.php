<?php
/** ebay 评价模型
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-27
 * Time: 14:46
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Channel\AccountModel as Channel_Accounts;
class EbayFeedBackModel extends BaseModel
{
    protected $table = 'ebay_feedback';
    protected $fillable = [
        'feedback_id',
        'channel_account_id',
        'commenting_user',
        'commenting_user_score',
        'comment_text',
        'comment_type',
        'ebay_item_id',
        'transaction_id',
        'comment_time',
    ];

    public $searchFields = ['ebay_item_id' => 'ItemId','commenting_user'=>'买家ID'];

       protected $rules = [
           'create' => [
           ],
           'update' => [
           ]
       ];

    public function channelAccount(){
        return $this->hasOne('App\Models\Channel\AccountModel', 'id', 'channel_account_id');

    }

    public function sku()
    {
        return $this->hasOne('APP\Models\Order\ItemModel', 'transaction_id', 'transaction_id');
    }


    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        //dd(Channel_Accounts::all()->pluck('account', 'account'));
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'ebay_feedback.transaction_id',
                'ebay_feedback.ebay_item_id',
                'ebay_feedback.commenting_user',
            ],
            'filterSelects' => [
                'ebay_feedback.comment_type' => config('crm.ebay.feedback'),
                'ebay_feedback.channel_account_id' =>  Channel_Accounts::all()->pluck('alias', 'id'),
                //'sku'
            ],
            'selectRelatedSearchs' => [
            ],
            'sectionSelect' => [],
        ];
    }


    /**
     * 退款统计
     * compact('begin','end')
     */
    public function getFeedBackStatistics($TimeAry){

        $feebdbacks = $this->where('comment_time','>=', $TimeAry['begin'])
                           ->where('comment_time','<=', $TimeAry['end'])->get();
        $Positive = 0; //好
        $Neutral  = 0; //中
        $Negative = 0; //差
        $Percentage = 0; //中差评百分比
        foreach ($feebdbacks as $feedback){
            switch ($feedback->comment_type){
                case 'Positive':
                    $Positive += 1;
                    break;
                case 'Neutral':
                    $Neutral += 1;
                    break;
                case 'Negative':
                    $Negative += 1;
                    break;
                default :
                    break;
            }

        }
       $total = $Positive + $Neutral + $Percentage;
       if($total == 0){
           $Percentage = 0;
       }else{
           $Percentage = round(($Negative + $Neutral) / $total,2)*100; //百分比
       }

        return compact('Positive','Neutral','Negative','Percentage');
    }

    /**
     * 账号名称
     */
    public function getChannelAccountAliasAttribute()
    {
        $account = $this->channelAccount;
        return  ! empty($account) ? $account->alias : '未知';
    }

}