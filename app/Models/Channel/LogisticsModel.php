<?php
/**
 * 渠道产品模型
 *
 * 2016-06-06
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Channel;

use App\Base\BaseModel;

class LogisticsModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_logistics';

    
    protected $fillable = ['id', 'name', 'created_at'];
}
