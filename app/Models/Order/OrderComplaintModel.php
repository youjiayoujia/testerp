<?php
/**
 * 产品模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/2/23
 * Time: 下午5:31
 */

namespace App\Models\Order;

use App\Base\BaseModel;
use App\Models\Order\ItemModel;

class OrderComplaintModel extends BaseModel
{
    protected $table = 'order_complaints';
	
	public $rules = [
        'create' => [
            
           
        ],
        'update' => [
            
        ]
    ];
	
    protected $guarded = [];

    public $searchFields = [
       
    ];

    public function orderItem()
    {
        return $this->belongsTo('App\Models\Order\ItemModel', 'order_item_id');
    }


}