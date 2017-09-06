<?php
/**
 * 顺分荷兰面单分拣码
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/12/7
 * Time: 下午2:09
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;

class ErpShunFenModel extends BaseModel
{
    public $table = 'erp_shunfen_hl';

    public $searchFields = ['code' => '国家简码', 'gh' => '挂号分拣码', 'py' => '平邮分拣码'];

    public $fillable = [
        'id',
        'country_en',
        'country_cn',
        'code',
        'gh',
        'py',
    ];
}