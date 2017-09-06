<?php
namespace App\Models\Purchase;
use Excel;
use App\Base\BaseModel;
use App\Models\ProductModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Product\ImageModel;
use App\Models\WarehouseModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchasePostageModel;

class PurchaseItemArrivalLogModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_item_arrival_logs';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			
        ]
    ];
    public $searchFields = ['sku'=>'sku'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['id','sku','purchase_item_id','arrival_num','good_num','bad_num','quality_time','purchase_order_id','user_id','is_second'];

    public function purchaseItem()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseItemModel', 'purchase_item_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'filterSelects' => [
                'is_second' => config('purchase.purchaseItemArrival.is_second'),
            ],
            'sectionSelect' => ['time' => ['created_at']],
        ];
    }
	
}