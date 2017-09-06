<?php
/**
 * EbaySku销量报表
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 17/1/5
 * Time: 下午3:29
 */

namespace App\Models\Order;

use App\Base\BaseModel;
use App\Models\Publish\Ebay\EbaySiteModel;

class EbaySkuSaleReportModel extends BaseModel
{
    public $table = 'ebay_sku_sale_report';

    public $searchFields = ['sku' => 'SKU'];

    public $fillable = [
        'sku',
        'channel_name',
        'site',
        'sale_different',
        'sale_different_proportion',
        'one_sale',
        'seven_sale',
        'fourteen_sale',
        'thirty_sale',
        'ninety_sale',
        'created_time',
        'status',
        'is_warning',
    ];

    //多重查询
    public function getMixedSearchAttribute()
    {
        $arr = [];
        $ebaySites = EbaySiteModel::all();
        foreach ($ebaySites as $ebaySite) {
            $arr[$ebaySite->site] = $ebaySite->site;
        }

        return [
            'filterFields' => [
                'sku',
            ],
            'filterSelects' => [
                'site' => $arr,
                'status' => config('item.status'),
            ],
        ];
    }

    public $rules = [
        'create' => [
        ],
        'update' => [
        ],
    ];

    //sku状态
    public function getStatusNameAttribute()
    {
        $config = config('item.status');
        return $config[$this->status];
    }

}