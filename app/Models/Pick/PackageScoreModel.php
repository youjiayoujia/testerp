<?php

namespace App\Models\Pick;

use App\Base\BaseModel;
use App\Models\Package\ItemModel;
use App\Models\PickListModel;

class PackageScoreModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'pick_package_scores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['picklist_id', 'package_id', 'package_score', 'created_at'];

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

    public function picklist()
    {
        return $this->belongsTo('App\Models\PickListModel', 'picklist_id', 'id');
    }
}
