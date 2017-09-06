<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/16
 * Time: 下午12:01
 */

namespace App\Models\Logistics\Rule;

use App\Base\BaseModel;

class AccountModel extends BaseModel
{
    protected $table = 'logistics_rule_accounts';

    protected $fillable = [
        'logistics_rule_id','account_id','created_at'
    ];

    public $searchFields = [''];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}