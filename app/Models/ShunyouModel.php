<?php
/**
 * 顺友挂号100*100
 *
 * Created by PhpStorm.
 * User: hejiancheng
 * Date: 2016-10-12
 */

namespace App\Models;

use App\Base\BaseModel;

class ShunyouModel extends BaseModel
{
    protected $table = 'shunyou_area';

    protected $fillable = [
        'id',
        'country_cn',
        'country_code',
        'area_code',
    ];
}