<?php
namespace App\Models\Product;
use App\Base\BaseModel;
class ProductVariationValueModel extends BaseModel
{
    protected $table = 'product_variation_values';
    protected $fillable = [
            'product_id','variation_id','variation_value','variation_value_id'
            ];

    public function VariationValue()
    {      
        return $this->belongsTo('App\Models\Catalog\VariationModel','variation_id');
    }

    public function productVariation(){
        return $this->belongsToMany('App\Models\Product\ProductVariationValueModel');
    }
}