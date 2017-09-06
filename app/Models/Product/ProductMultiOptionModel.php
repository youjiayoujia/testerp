<?php

namespace App\Models\Product;

use App\Base\BaseModel;

class ProductMultiOptionModel extends BaseModel
{
	protected $table = 'product_multi_options';

	protected $fillable = [
        'it_name', 'it_description', 'it_keywords', 
        'de_name', 'de_description', 'de_keywords', 
        'fr_name', 'fr_description', 'fr_keywords',
        'zh_name', 'zh_description', 'zh_keywords',
        'en_name', 'en_description', 'en_keywords',
        'product_id','channel_id','spu_id',
    ];

    // 规则验证
    public $rules = [
        'create' => [   
                //'it_name' => 'required|max:255|unique:product_requires,name',
        ],
        'update' => [   
                //'it_name' => 'required|max:255|unique:product_requires,name, {id}',
        ]
    ];

    //查询
    public $searchFields = ['name'];
    
}