<?php

namespace App\Models\Product;

use App\Base\BaseModel;

class SupplierLevelModel extends BaseModel
{
    protected $table = 'product_supplier_levels';

    protected $fillable = [
            'name', 'description'
            ];

    public $rules = [
        'create' => [   
            'name' => 'required|unique:product_supplier_levels',
        ],
        'update' => [   
            'name' => 'required|unique:product_supplier_levels,name,{id}',
        ]
    ];
    //查询
    public $searchFields = ['name'=>'名称', 'description'=>'描述'];
}