<?php
namespace App\Models\Product;
use App\Base\BaseModel;
class ProductFeatureValueModel extends BaseModel
{
    protected $table = 'product_feature_values';
    protected $fillable = [
            'spu_id','feature_id','feature_value','feature_value_id'
            ];
    public function featureName()
    {
        return $this->belongsTo('App\Models\Catalog\FeatureModel','feature_id');
    }
}
