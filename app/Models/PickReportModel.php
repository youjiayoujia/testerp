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

class PickReportModel extends BaseModel
{
    protected $table = 'pick_reports';

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'single',
        'singleMulti',
        'multi',
        'missing_pick',
        'today_pick',
        'today_picklist',
        'day_time',
        'today_picklist_undone',
        'more_than_twenty_four'
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