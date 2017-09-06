<?php
/*Time:2016-12-9
 *账号token 多种类型token
 *User:hejiancheng
 */

namespace App\Models;

use App\Base\BaseModel;

class AccounttokenModel extends BaseModel
{
    protected $table = 'account_token_type';

    protected $fillable = [
        'account',
        'account_token',
        'type',
        'grant',
        'remark',
    ];

}