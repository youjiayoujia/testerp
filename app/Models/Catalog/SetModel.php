<?php

namespace App\Models\Catalog;

use App\Base\BaseModel;

class SetModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['catalog_id', 'name'];

    public function Catalog()
    {
        return $this->belongsTo('App\Models\CatalogModel','catalog_id');
    }

    public function values()
    {
        return $this->hasMany('App\Models\Catalog\SetValueModel','set_id');
    }
}
