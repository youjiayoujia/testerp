<?php

namespace App\Models\Product;

use App\Base\BaseModel;
use Tool;

class SupplierAttachmentModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'suppliers_attachment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['supplier_id', 'filename'];


}
