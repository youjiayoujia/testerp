<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-07-01
 * Time: 16:42
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
class EbayShippingModel extends BaseModel
{

    protected $table = 'ebay_shipping';

    protected $fillable = [
        'site_id',
        'description',
        'international_service',
        'shipping_service',
        'shipping_service_id',
        'shipping_time_max',
        'shipping_time_min',
        'valid_for_selling_flow',
        'shipping_category',
        'shipping_carrier',
    ];

    protected $searchFields = [];

    /*   protected $rules = [
           'create' => [
               'seller_code' => 'required',
               'user_id' => 'required',
           ],
           'update' => [
               'seller_code' => 'required',
               'user_id' => 'required',

           ]
       ];*/


}