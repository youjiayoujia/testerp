<?php

namespace App\Models\Purchase;

use App\Base\BaseModel;

class PurchaseOrderConfirmModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_confirms';

    public $rules = [
        'create' => [
            
        ],
        'update' => [
        
        ]
    ];

    public $searchFields = ['po_id'=>'采购单id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['po_id', 'status','real_money','no_delivery_money','reason','credential','po_user','refund_time','create_user'];

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseOrderModel', 'po_id');
    }

    public function createUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'create_user');
    }

    public function purchaseUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'po_user');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'filterSelects' => ['status'=>config('purchase.purchaseOrder.confirm_write_off')],
        ];
    }

}
