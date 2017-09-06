<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2016-09-19
 * Time: 11:35
 */
namespace App\Models\Publish\Ebay;

use App\Base\BaseModel;



class EbayStoreCategoryModel extends BaseModel
{
    protected $table = 'ebay_store_category';
    protected $fillable = [
        'account_id',
        'store_category',
        'store_category_name',
        'level',
        'category_parent',
    ];

    protected $searchFields = [];

    protected $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];


    public function getALLCategory($account_id){
        $result_all=array();
        $result = $this->where('account_id',$account_id)->get([ 'account_id',
            'store_category',
            'store_category_name',
            'level',
            'category_parent'])->toArray();
        foreach($result as $re){
            if($re['level']==1){
                $result_all['root'][] = $re;
            }else{
                $result_all['child'][$re['category_parent']][] = $re;
            }
        }

        return  $result_all;



    }
}