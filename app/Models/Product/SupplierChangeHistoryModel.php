<?php

namespace App\Models\Product;

use App\Base\BaseModel;
use App\Models\UserModel;

class SupplierChangeHistoryModel extends BaseModel
{
    protected $table = 'supplier_change_historys';

    protected $fillable = [
            'supplier_id', 'from', 'to', 'adjust_by', 'created_at'
            ];

    public $rules = [
        'create' => [   
        ],
        'update' => [   
        ]
    ];
    public $searchFields = [];


    public function supplierName()
    {
        return $this->belongsTo('App\Models\Product\SupplierModel', 'supplier_id', 'id');
    }

    public function adjustByName()
    {
        return $this->belongsTo('App\Models\UserModel', 'adjust_by', 'id');
    }

    public function fromName()
    {
        return $this->belongsTo('App\Models\UserModel', 'from', 'id');
    }

    public function toName()
    {
        return $this->belongsTo('App\Models\UserModel', 'to', 'id');
    }
}