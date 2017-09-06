<?php
/**
 * 分拣码模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/11/15
 * Time: 下午3:31
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ErpEubModel extends BaseModel
{
    public $table = 'erp_eub';

    public $searchFields = ['code' => '分拣编码', 'zip' => '邮编'];

    public $fillable = [
        'id',
        'code',
        'zip'
    ];
}