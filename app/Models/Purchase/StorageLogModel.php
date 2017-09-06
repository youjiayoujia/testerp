<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\Purchase\PurchaseItemModel;

class StorageLogModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'storage_log';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = ['id','purchaseItemId','user_id','storage_quantity'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['id','purchaseItemId','user_id','storage_quantity'];
	public function purchaseItem()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseItemModel', 'purchaseItemId');
    } 	
	
}