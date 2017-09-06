<?php

namespace App\Models\Item;

use App\Base\BaseModel;

class ItemPrepareSupplierModel extends BaseModel
{
    protected $table = 'item_prepare_suppliers';

	protected $guarded = [];

    //查询
    public $searchFields = ['id'=>'id'];

    

}
