<?php
/**
 * EBAY销售额统计
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 17/1/16
 * Time: 下午2:53
 */

namespace App\Models\Order;

use App\Base\BaseModel;
use App\Models\RoleModel;
use App\Models\User\UserRoleModel;

class EbayAmountStatisticsModel extends BaseModel
{
    public $table = 'ebay_sku_amount_statistics';

    public $searchFields = [];

    public $fillable = [
        'channel_name',
        'user_id',
        'prefix',
        'january_sales',
        'profit_rate',
        'january_publish',
        'january_publish_quantity',
        'january_publish_amount',
        'january_publish_ratio',
        'january_advertisement_rate',
        'sku_sell_rate',
        'yesterday_publish',
        'created_date',
    ];

    //多重查询
    public function getMixedSearchAttribute()
    {
        $arr = [];
        $roleId = RoleModel::where('role', 'ebay_staff')->first()->id;
        $userRoles = UserRoleModel::where('role_id', $roleId)->get();
        foreach ($userRoles as $userRole) {
            $arr[$userRole->user_id] = $userRole->user->name;
        }

        return [
            'filterFields' => [
                'created_date',
            ],
            'filterSelects' => [
                'user_id' => $arr,
            ],
        ];
    }

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }

}