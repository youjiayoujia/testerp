<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkuPublishRecords extends Model
{
    //
    protected $table = "sku_publish_records";
    protected $fillable = ['SKU','userID','publishTime','platTypeID','publishPlat','sellerAccount','itemNumber','publishViewUrl'];
}
