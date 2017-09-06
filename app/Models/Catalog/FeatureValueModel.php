<?php

namespace App\Models\Catalog;

use App\Base\BaseModel;

class FeatureValueModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feature_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['feature_id', 'name'];

    public function feature()
    {
        return $this->belongsTo('App\Models\Catalog\FeatureModel','feature_id');
    }
}
