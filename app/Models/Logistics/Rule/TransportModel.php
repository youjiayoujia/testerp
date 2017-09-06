<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/8/30
 * Time: 上午9:45
 */

namespace App\Models\Logistics\Rule;

use App\Base\BaseModel;

class TransportModel extends BaseModel
{
    protected $table = 'logistics_rule_transports';

    protected $fillable = [
        'logistics_rule_id','transport_id','created_at'
    ];

    public $searchFields = [''];

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];
}