<?php

namespace App\Models\Purchase;

use App\Base\BaseModel;

class PurchasesModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];

    public $searchFields = ['id'=>'id','sku'=>'sku','c_name'=>'c_name'];
    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

}