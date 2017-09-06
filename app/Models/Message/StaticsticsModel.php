<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2017/1/3
 * Time: 15:31
 */
namespace App\Models\Message;
use App\Base\BaseModel;
use App\Models\UserModel;

class StaticsticsModel extends BaseModel
{
    protected $table = 'message_statistics';

    protected $guarded = [];

    public $appends = [
        'user_name'
    ];

    /**
     * 更多搜索
     * @return array
     */
    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [
                'message_statistics.compute_time'
            ],
            'filterSelects' => [
                'message_statistics.user_id' => UserModel::all()->pluck('name','id'),
            ],
            'selectRelatedSearchs' => [
            ],
            'sectionSelect' => ['time'=>['created_at']],
        ];
    }

    public function user(){
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }

    public function getUserNameAttribute(){
        return $this->user ? $this->user->name : '未知';
    }

}
