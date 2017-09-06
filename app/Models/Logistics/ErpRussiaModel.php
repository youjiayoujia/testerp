<?php
/**
 * 俄罗斯平邮挂号面单
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/11/17
 * Time: 下午4:36
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ErpRussiaModel extends BaseModel
{
    public $table = 'erp_russia';

    public $searchFields = ['country_code' => '国家简码'];

    public $fillable = [
        'id',
        'country_code',
        'express_code',
        'type',
    ];
}