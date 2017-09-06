<?php

namespace App\Models\Pick;

use App\Base\BaseModel;
use App\Models\Package\ItemModel;
use App\Models\PickListModel;

class ListItemPackageModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'picklistitem_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['picklist_item_id', 'package_id', 'created_at'];

    // 规则验证
    public $rules = [
        'create' => [
        ],
        'update' => [
        ]
    ];

    public function package()
    {
        return $this->belongsTo('App\Models\PackageModel', 'package_id', 'id');
    }

    public function picklistItem()
    {
        return $this->belongsTo('App\Models\Pick\ListItemModel', 'picklist_item_id', 'id');
    }
}
