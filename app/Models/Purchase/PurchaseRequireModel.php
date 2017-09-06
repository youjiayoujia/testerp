<?php
namespace App\Models\Purchase;
use App\Base\BaseModel;
use App\Models\ItemModel;

class PurchaseRequireModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_requires';
    public $rules = [
        'create' => [
            
        ],
        'update' => [
 			 
        ]
    ];
    public $searchFields = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = ['quantity','status','item_id'];
	public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id');
    } 
   
	
}