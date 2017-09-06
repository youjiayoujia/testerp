<?php
/**
 * 包裹产品产品模型
 *
 * Created by PhpStorm.
 * User: Vincent
 * Date: 2016-03-10
 */

namespace App\Models;

use App\Base\BaseModel;

class PackReportModel extends BaseModel
{
    protected $table = 'pack_reports';

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'yesterday_send',
        'single',
        'singleMulti',
        'multi',
        'all_worktime',
        'error_send',
        'day_time'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }
}