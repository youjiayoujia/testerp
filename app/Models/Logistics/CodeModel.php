<?php
/**
 * 跟踪号模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/25
 * Time: 下午3:16
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class CodeModel extends BaseModel
{
    protected $table = 'logistics_codes';

    protected $fillable = [
        'logistics_id',
        'code',
        'package_id',
        'used_at',
        'status',
    ];

    public $searchFields = ['logistics_id' => '物流方式', 'code' => '跟踪号', 'package_id' => '包裹ID'];

    public $rules = [
        'create' => [
            'logistics_id' => 'required',
            'code' => 'required',
        ],
        'update' => [
            'logistics_id' => 'required',
            'code' => 'required',
            'status' => 'required',
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'logistics_id', 'id');
    }

}