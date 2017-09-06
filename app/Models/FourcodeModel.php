<?php
/**
 * 4px新加坡平邮小包面单国家分区
 *
 * Created by PhpStorm.
 * User: hejiancheng
 * Date: 16/10/12
 * Time: 12:55
 */

namespace App\Models;

use App\Base\BaseModel;

class FourcodeModel extends BaseModel
{
    protected $table = 'fourpx_country_code';

    protected $fillable = [
        'id',
        'country_name',
        'code',
        'partition',
    ];

}