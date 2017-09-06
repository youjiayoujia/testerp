<?php
namespace App\Models\Product;
use App\Base\BaseModel;
class ProductAttributeValueModel extends BaseModel
{
    protected $table = 'product_attribute_values';
    protected $fillable = [
            'product_id','attribute_id','attribute_value','attribute_value_id'
            ];
    public function AttributeValue()
    {      
        return $this->belongsTo('App\Models\Catalog\AttributeModel','attribute_id');
    }
}