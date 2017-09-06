<?php

namespace App\Models\Stock;

use App\Base\BaseModel;
use App\Models\Warehouse\PositionModel;
use App\Models\ItemModel;

class InOutModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stock_in_outs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quantity', 'amount', 'inner_type', 'remark', 'relation_id', 'stock_id', 'created_at', 'outer_type'];

    // 用于查询
    public $searchFields = ['id' => 'ID'];
    
    public function getMixedSearchAttribute()
    {
        return [
            'filterFields' => ['id'],
            'relatedSearchFields' => [],
            'doubleRelatedSearchFields' => ['stock' => ['item' => ['sku']]],
            'filterSelects' => ['outer_type' => ['IN' => '入库', 'OUT' => '出库'], 'inner_type' => config('inout.INNER_TYPE')],
            'selectRelatedSearchs' => [
            ],
            'sectionSelect' => [],
        ];
    }
    
    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Models\WarehouseModel', 'warehouse_id', 'id');
    }

    /**
     * get the relationship between the two module 
     *
     * @param none
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }
    
    /**
     *  make the accessor. 
     *  get the name by key in config.
     *
     *  @return name(by type)
     */
    public function getTypeNameAttribute()
    {
        $buf = config('inout.ALL_TYPE');
        $type = $this->outer_type.'.'.$this->inner_type;
        if(array_key_exists($type, $buf))
            return $buf[$type];
    }

    /**
     * return the relationship between the two module 
     *
     *  @return
     *
     */
    public function stock()
    {
        return $this->belongsTo('App\Models\StockModel', 'stock_id', 'id');
    }
    
    /**
     *  get the relation between the two Model 
     *
     *  @return none
     *
     */
    public function stockAdjustment()
    {
        return $this->belongsTo('App\Models\Stock\AdjustmentModel', 'relation_id', 'id');
    }

    /**
     * accessor get the relation name 
     *
     * @return name
     *
     */
    public function getRelationNameAttribute()
    {
        switch ($this->inner_type) {
            case 'ADJUSTMENT':
                return $this->stockAdjustment ? $this->stockAdjustment->adjust_form_id : '';
                break;
            case 'ALLOTMENT':
                return $this->stockAllotment ? $this->stockAllotment->allotment_id : '';
                break;
            case 'OVERSEA_ALLOTMENT':
                return $this->overseaAllotment ? $this->overseaAllotment->id : '';
                break;
            case 'INVENTORY_PROFIT':
                return $this->stockTaking ? $this->stockTaking->taking_id : '';
                break;
            case 'SHORTAGE':
                return $this->stockTaking ? $this->stockTaking->taking_id : '';
                break;
            case 'MAKE_ACCOUNT':
                return '库存导入';
                break;
            case 'PACKAGE':
                return $this->packageItem ? (($this->packageItem->package ? ($this->packageItem->package->order ? $this->packageItem->package->order->id : '') : ''). ' : ' . ($this->packageItem->package ? $this->packageItem->package->id : '')) : '';
                break;
            case 'CANCEL':
                return $this->relation_id;
                break;
            case 'OVERSEA_IN':
                return '海外仓调拨入库';
                break;
        }
    }

    public function packageItem()
    {
        return $this->belongsTo('App\Models\Package\ItemModel', 'relation_id', 'id');
    }

    /**
     * get the relation between the two Model
     * 
     *  @return relation
     *
     */
    public function stockAllotment()
    {
        return $this->belongsTo('App\Models\Stock\AllotmentModel', 'relation_id', 'id');
    }

    public function overseaAllotment()
    {
        return $this->belongsTo('App\Models\Oversea\Allotment\AllotmentModel', 'relation_id', 'id');
    }

    /**
     * get the relation between the two Model
     * 
     *  @return relation
     *
     */
    public function stockTaking()
    {
        return $this->belongsTo('App\Models\Stock\TakingModel', 'relation_id', 'id');
    }

    /**
     * get the relation between the two Model
     * 
     *  @return relation
     *
     */
    public function stockPurchase()
    {
        return $this->belongsTo('App\Models\Purchase\PurchaseOrderModel', 'relation_id', 'id');
    }
}
