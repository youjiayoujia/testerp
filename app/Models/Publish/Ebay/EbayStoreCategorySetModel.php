<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-09-19
 * Time: 11:36
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;
use App\Models\Publish\Ebay\EbaySiteModel;
use App\Models\CatalogModel;
class EbayStoreCategorySetModel extends BaseModel
{
    protected $table = 'ebay_store_category_set';
    protected $fillable = [
        'site',
        'warehouse',
        'category',
        'category_description',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function getMixedSearchAttribute()
    {
        $ebaySite = new EbaySiteModel();
        $erpCategory = new CatalogModel();

        return [
            'filterSelects' => [
                'site' => $ebaySite->getSite('site_id'),
                'warehouse' => config('ebaysite.warehouse'),
                'category' => $erpCategory->get()->lists('name', 'id'),

            ]
        ];
    }
    public function ebaySite()
    {
        return $this->hasOne('App\Models\Publish\Ebay\EbaySiteModel', 'site_id', 'site');
    }


    public function erpCategory()
    {
        return $this->hasOne('App\Models\CatalogModel', 'id', 'category');
    }

    public function getCategoryByAccount($account_id, $category, $site, $warehouse)
    {
        $return = [];
        $result = $this->where(['category' => $category, 'site' => $site, 'warehouse' => $warehouse])->first();
        if(!empty($result)){
            $category_description = json_decode($result->category_description, true);
            $return['store_category'] = isset($category_description[$account_id]['store_category']) ? $category_description[$account_id]['store_category'] : '';
            $return['description_id'] = isset($category_description[$account_id]['description_id']) ? $category_description[$account_id]['description_id'] : '';
        }else{
            return false;
        }


        return $return;

    }
}