<?php
/**
 * 渠道模型
 *
 * 2016-01-04
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models;

use App\Base\BaseModel;

class ChannelModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'driver',
        'brief',
        'created_at',
    ];

    public $searchFields = ['name' => '名称'];

    protected $rules = [
        'create' => ['name' => 'required|unique:channels,name', 'driver' => 'required'],
        'update' => ['name' => 'required|unique:channels,name,{id}', 'driver' => 'required']
    ];

    public function accounts()
    {
        return $this->hasMany('App\Models\Channel\AccountModel', 'channel_id', 'id');
    }

    public function logisticsChannelName()
    {
        return $this->hasMany('App\Models\Logistics\ChannelNameModel', 'channel_id', 'id');
    }

    public function getAutoReplyChannel()
    {
        $drivers = ['wish', 'aliexpress'];
        return $this->select(['id','name'])->whereIn('driver', $drivers)->take(3)->get();

    }

}
