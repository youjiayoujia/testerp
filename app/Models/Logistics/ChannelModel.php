<?php
/**
 * 物流渠道模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/9
 * Time: 下午2:47
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ChannelModel extends BaseModel
{
    protected $table = 'logistics_channel';

    public $searchFields = [];

    protected $fillable = [
        'id',
        'logistics_id',
        'channel_id',
        'url',
        'is_up',
        'delivery',
    ];

    public $rules = [
        'create' => [
            'logistics_id' => 'required',
            'channel_id' => 'required',
        ],
        'update' => [
            'logistics_id' => 'required',
            'channel_id' => 'required',
        ],
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

}