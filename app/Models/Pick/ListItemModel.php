<?php

namespace App\Models\Pick;

use App\Base\BaseModel;
use App\Models\Package\ItemModel;
use App\Models\PickListModel;

class ListItemModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'picklist_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['picklist_id', 'type', 'warehouse_position_id', 'logistic_id', 'item_id', 'sku', 'packed_quantity', 'quantity', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function pickListItemPackages()
    {
        return $this->hasMany('App\Models\Pick\ListItemPackageModel', 'picklist_item_id', 'id');
    }

    public function packages()
    {
        return $this->belongsToMany('App\Models\PackageModel', 'picklistitem_packages', 'picklist_item_id', 'package_id');
    }

    public function items()
    {
        return $this->belongsTo('App\Models\ItemModel', 'item_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Warehouse\PositionModel', 'warehouse_position_id', 'id');
    }

}
