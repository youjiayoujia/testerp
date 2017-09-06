<?php
/**
 * 4px新加坡平邮小包面单数字分区
 *
 * Created by PhpStorm.
 * User: hejiancheng
 * Date: 16/10/12
 * Time: 12:55
 */

namespace App\Models;

use App\Base\BaseModel;

class FourModel extends BaseModel
{
    protected $table = 'erp_num_code';

    protected $fillable = [
        'fourpx_num',
        'fourpx_country_code',
    ];

}