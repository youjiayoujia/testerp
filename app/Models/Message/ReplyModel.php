<?php
/**
 * 信息回复模型
 *
 * 2016-01-18
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Message;

use App\Base\BaseModel;

class ReplyModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_replies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_id',
        'to',
        'to_email',
        'title',
        'content',
        'status',
    ];

    public $rules = [
        'create' => [
            'to' => 'required',
            'to_email' => 'required',
            'title' => 'required',
            'content' => 'required',
        ],
        'update' => [
            'title' => 'required',
            'content' => 'required',
        ]
    ];
    public $searchFields = ['id'=> 'ID', 'to_email'=>'收件邮箱'];

    public function message()
    {
        return $this->belongsTo('App\Models\Message\MessageModel', 'message_id');
    }
    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [
            ],
            'filterFields' => [
                'message_id',
            ],
            'filterSelects' => [
                'status' => config('message.reply.status'),

            ],
            'selectRelatedSearchs' => [],
            'sectionSelect' => ['time'=>['created_at']],
        ];
    }

}
