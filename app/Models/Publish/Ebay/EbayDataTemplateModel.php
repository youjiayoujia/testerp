<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-14
 * Time: 16:10
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Publish\Ebay\EbaySiteModel;
class EbayDataTemplateModel extends BaseModel
{
    protected $table = 'ebay_data_template';
    protected $fillable = [
        'name',
        'site',
        'warehouse',
        'start_weight',
        'end_weight',
        'start_price',
        'end_price',
        'location',
        'country',
        'postal_code',
        'dispatch_time_max',
        'buyer_requirement',
        'return_policy',
        'shipping_details'
    ];

    public $searchFields = ['name'=>'模板名称'];

    protected $rules = [
        'create' => [
            'name',
            'site',
            'warehouse',
            'start_weight',
            'end_weight',
            'start_price',
            'end_price',
            'location',
            'country',
            'postal_code',
            'dispatch_time_max',
            'buyer_requirement',
            'return_policy',
            'shipping_details'
        ],
        'update' => [
            'name',
            'site',
            'warehouse',
            'start_weight',
            'end_weight',
            'start_price',
            'end_price',
            'location',
            'country',
            'postal_code',
            'dispatch_time_max',
            'buyer_requirement',
            'return_policy',
            'shipping_details'
        ]
    ];

    public function getMixedSearchAttribute()
    {
        $ebaySite = new EbaySiteModel();
        return [
            'filterSelects' => [
                'site' => $ebaySite->getSite('site_id'),
                'warehouse' => config('ebaysite.warehouse')
            ]
        ];
    }


    public function ebayShipping()
    {
        return $this->hasMany('App\Models\Publish\Ebay\EbayShippingModel','site_id','site');
    }

    public function ebaySite()
    {
        return $this->hasOne('App\Models\Publish\Ebay\EbaySiteModel','site_id','site');
    }

}