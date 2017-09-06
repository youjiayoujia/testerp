<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;

class PurchasePostageModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_postages';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = ['id'=>'ID','purchase_order_id'=>'采购单号','post_coding'=>'运单号'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['id','purchase_item_id','purchase_order_id','post_coding','postage','user_id']; 	

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseOrderModel','purchase_order_id','id');
    }


    





}