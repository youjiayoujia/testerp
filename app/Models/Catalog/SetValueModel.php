<?php

namespace App\Models\Catalog;

use App\Base\BaseModel;

class SetValueModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'set_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['set_id', 'name'];

    public function set()
    {
        return $this->belongsTo('App\Models\Catalog\SetModel','set_id');
    }
}
