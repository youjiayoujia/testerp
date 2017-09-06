<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models\Package;

use App\Base\BaseModel;

class AllReportModel extends BaseModel
{
    public $table = 'package_all_reports';

    protected $fillable = [
        'channel_id',
        'warehouse_id',
        'wait_send',
        'sending',
        'sended',
        'more',
        'less',
        'daily_send',
        'need',
        'daily_sales',
        'month_sales',
        'rate',
        'time_rate',
        'daily_averate_sale',
        'day_time'
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\ChannelModel', 'channel_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }
}