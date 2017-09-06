<?php
/**
 * 信息回复模型
 *
 * 2016-01-18
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Message;

use App\Base\BaseModel;

class AutoReplyRulesModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'auto_reply_rules';

    protected $fillable = [
        'status',
        'message_keywords',
        'reply_keywords',
        'label_keywords',
        'filter_start_time',
        'filter_end_time',
        'name',
        'create_by',
        'template',
        'type_time_filter',
        'type_shipping_one_month',
        'type_shipping_one_two_month',
        'type_shipping_fifty_day',
        'type_within_tuotou',
        'channel_id',
        'type_ebay_address',
        'type_ebay_color',
        'type_ebay_twenty_five_day',
    ];

    public $rules = [
        'create' => [
            'name' => 'required',
            'status' => 'required',
            'channel_id' => 'required|numeric',
            'create_by' => 'required|numeric',
            'template' => 'required',
        ],
        'update' => [
            'name' => 'required',
            'status' => 'required',
            'channel_id' => 'required|numeric',
            'create_by' => 'required|numeric',
            'template' => 'required',
        ],
    ];
    //public $searchFields = ['id'=> 'ID', 'to_email'=>'收件邮箱'];



    public function channel(){
        return $this->belongsTo('App\Models\ChannelModel','channel_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'create_by' , 'id');
    }
    public function getChannelNameAttribute()
    {
        return ! empty($this->channel->name) ? $this->channel->name : '无';
    }

    public function getUserNameAttribute()
    {
        return  ! empty($this->user->name) ? $this->user->name : '无';
    }




}
