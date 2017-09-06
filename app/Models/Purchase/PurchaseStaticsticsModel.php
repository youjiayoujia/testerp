<?php

namespace App\Models\Purchase;

use App\Base\BaseModel;

class PurchaseStaticsticsModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_staticstics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];

    public $searchFields = ['id'=>'id'];
    
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'purchase_adminer');
    }

    public function getMixedSearchAttribute()
    {
        return [
            'relatedSearchFields' => [],
            'filterFields' => [],
            'filterSelects' => [],
            'selectRelatedSearchs' => [],
            'sectionSelect' => ['time' => ['get_time']],
        ];
    }
}