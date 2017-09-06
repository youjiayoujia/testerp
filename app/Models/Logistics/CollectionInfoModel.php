<?php
/**
 * 收款信息模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/5
 * Time: 下午4:41
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class CollectionInfoModel extends BaseModel
{
    public $table = 'logistics_collection_infos';

    public $searchFields = ['bank' => '收款银行', 'account' => '收款账户'];

    public $fillable = [
        'id',
        'bank',
        'account',
    ];

    public $rules = [
        'create' => [
            'bank' => 'required',
            'account' => 'required',
        ],
        'update' => [
            'bank' => 'required',
            'account' => 'required',
        ],
    ];
}