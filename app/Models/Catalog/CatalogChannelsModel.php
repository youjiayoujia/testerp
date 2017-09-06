<?php

namespace App\Models\Catalog;

use App\Base\BaseModel;

class CatalogChannelsModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catalog_channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['rate'];

    protected  $guarded =[];
}
