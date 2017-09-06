<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/6/21
 * Time: 14:16
 */
namespace App\Models\Message;
use App\Base\BaseModel;
class AccountModel extends BaseModel{
    protected $table = 'message_accounts';
    
    protected $fillable = [
        'account',
        'secret',
        'token'
    ];

    public function labels()
    {
        return $this->hasMany('App\Models\Message\LabelModel', 'account_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message\MessageModel', 'account_id');
    }

    public function replies()
    {
        return $this->hasManyThrough('App\Models\Message\ReplyModel', 'App\Models\Message\MessageModel',
            'account_id', 'message_id');
    }
    public function foremail()
    {
        return $this->hasManyThrough('App\Models\Message\ForemailModel', 'App\Models\MessageModel',
            'account_id', 'message_id');
    }
}