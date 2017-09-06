<?php

namespace App\Models\Pick;

use App\Base\BaseModel;
use App\Models\StockModel;

class ErrorListModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'picklist_error_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'packageNum', 'warehouse_position_id', 'warehouse_id', 'quantity', 'created_at'];

    public $searchFields = ['id' => 'ID'];
    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function getMixedSearchAttribute()
    {
        return [
            'filterFields' => [
            ],
            'filterSelects' => [
            ],
            'sectionSelect' => [
            ],
            'relatedSearchFields' => [ 
                'item' => ['sku'], 
                'warehouse' => ['name'], 
                'warehousePosition' => ['name']
            ],
            'selectRelatedSearchs' => [
            ]
        ];
    }

    public function item()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    public function warehousePosition()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

    public function getQuantityAttribute()
    {
        $stock = StockModel::where(['item_id' => $this->item_id, 'warehouse_position_id' => $this->warehouse_position_id])->first();
        if(!$stock) {
            return false;
        }
        return $stock->all_quantity;
    }
}
